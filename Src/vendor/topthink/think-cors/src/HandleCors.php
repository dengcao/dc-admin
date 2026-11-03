<?php

namespace think\cors;

use Closure;
use think\Config;
use think\Request;
use think\Response;

class HandleCors
{
    /** @var string[] */
    protected $paths = [];
    /** @var string[] */
    protected $allowedOrigins = [];
    /** @var string[] */
    protected $allowedOriginsPatterns = [];
    /** @var string[] */
    protected $allowedMethods = [];
    /** @var string[] */
    protected $allowedHeaders = [];
    /** @var string[] */
    private $exposedHeaders      = [];
    protected $supportsCredentials = false;
    protected $maxAge              = 0;

    protected $allowAllOrigins = false;
    protected $allowAllMethods = false;
    protected $allowAllHeaders = false;

    public function __construct(Config $config)
    {
        $options = $config->get('cors', []);

        $this->paths                  = $options['paths'] ?? $this->paths;
        $this->allowedOrigins         = $options['allowed_origins'] ?? $this->allowedOrigins;
        $this->allowedOriginsPatterns = $options['allowed_origins_patterns'] ?? $this->allowedOriginsPatterns;
        $this->allowedMethods         = $options['allowed_methods'] ?? $this->allowedMethods;
        $this->allowedHeaders         = $options['allowed_headers'] ?? $this->allowedHeaders;
        $this->exposedHeaders         = $options['exposed_headers'] ?? $this->exposedHeaders;
        $this->supportsCredentials    = $options['supports_credentials'] ?? $this->supportsCredentials;

        $maxAge = $this->maxAge;
        if (array_key_exists('max_age', $options)) {
            $maxAge = $options['max_age'];
        }
        $this->maxAge = $maxAge === null ? null : (int) $maxAge;

        // Normalize case
        $this->allowedHeaders = array_map('strtolower', $this->allowedHeaders);
        $this->allowedMethods = array_map('strtoupper', $this->allowedMethods);

        // Normalize ['*'] to true
        $this->allowAllOrigins = in_array('*', $this->allowedOrigins);
        $this->allowAllHeaders = in_array('*', $this->allowedHeaders);
        $this->allowAllMethods = in_array('*', $this->allowedMethods);

        // Transform wildcard pattern
        if (!$this->allowAllOrigins) {
            foreach ($this->allowedOrigins as $origin) {
                if (strpos($origin, '*') !== false) {
                    $this->allowedOriginsPatterns[] = $this->convertWildcardToPattern($origin);
                }
            }
        }
    }

    /**
     * @param Request $request
     * @param Closure $next
     * @return Response
     */
    public function handle($request, Closure $next)
    {
        if (!$this->hasMatchingPath($request)) {
            return $next($request);
        }

        if ($this->isPreflightRequest($request)) {
            return $this->handlePreflightRequest($request);
        }

        /** @var Response $response */
        $response = $next($request);

        return $this->addPreflightRequestHeaders($response, $request);
    }

    protected function addPreflightRequestHeaders(Response $response, Request $request): Response
    {
        $this->configureAllowedOrigin($response, $request);

        if ($response->getHeader('Access-Control-Allow-Origin')) {
            $this->configureAllowCredentials($response, $request);
            $this->configureAllowedMethods($response, $request);
            $this->configureAllowedHeaders($response, $request);
            $this->configureExposedHeaders($response, $request);            
            $this->configureMaxAge($response, $request);
        }

        return $response;
    }

    protected function configureAllowedOrigin(Response $response, Request $request): void
    {
        if ($this->allowAllOrigins === true && !$this->supportsCredentials) {
            $response->header(['Access-Control-Allow-Origin' => '*']);
        } elseif ($this->isSingleOriginAllowed()) {
            $response->header(['Access-Control-Allow-Origin' => array_values($this->allowedOrigins)[0]]);
        } else {
            if ($this->isCorsRequest($request) && $this->isOriginAllowed($request)) {
                $response->header(['Access-Control-Allow-Origin' => (string) $request->header('Origin')]);
            }
        }
    }

    protected function configureAllowCredentials(Response $response, Request $request): void
    {
        if ($this->supportsCredentials) {
            $response->header(['Access-Control-Allow-Credentials' => 'true']);
        }
    }

    protected function configureAllowedMethods(Response $response, Request $request): void
    {
        if ($this->allowAllMethods === true) {
            $allowMethods = strtoupper((string) $request->header('Access-Control-Request-Method'));
        } else {
            $allowMethods = implode(', ', $this->allowedMethods);
        }

        $response->header(['Access-Control-Allow-Methods' => $allowMethods]);
    }

    protected function configureAllowedHeaders(Response $response, Request $request): void
    {
        if ($this->allowAllHeaders === true) {
            $allowHeaders = (string) $request->header('Access-Control-Request-Headers');
        } else {
            $allowHeaders = implode(', ', $this->allowedHeaders);
        }
        $response->header(['Access-Control-Allow-Headers' => $allowHeaders]);
    }

    protected function configureExposedHeaders(Response $response, Request $request): void
    {
        if ($this->exposedHeaders) {
            $exposeHeaders = implode(', ', $this->exposedHeaders);
            $response->header(['Access-Control-Expose-Headers' => $exposeHeaders]);
        }
    }

    protected function configureMaxAge(Response $response, Request $request): void
    {
        if ($this->maxAge !== null) {
            $response->header(['Access-Control-Max-Age' => (string) $this->maxAge]);
        }
    }

    protected function handlePreflightRequest(Request $request)
    {
        $response = response('', 204);

        return $this->addPreflightRequestHeaders($response, $request);
    }

    protected function isCorsRequest(Request $request)
    {
        return !!$request->header('Origin');
    }

    protected function isPreflightRequest(Request $request)
    {
        return $request->method() === 'OPTIONS' && $request->header('Access-Control-Request-Method');
    }

    protected function isSingleOriginAllowed(): bool
    {
        if ($this->allowAllOrigins === true || count($this->allowedOriginsPatterns) > 0) {
            return false;
        }

        return count($this->allowedOrigins) === 1;
    }

    protected function isOriginAllowed(Request $request): bool
    {
        if ($this->allowAllOrigins === true) {
            return true;
        }

        $origin = (string) $request->header('Origin');

        if (in_array($origin, $this->allowedOrigins)) {
            return true;
        }

        foreach ($this->allowedOriginsPatterns as $pattern) {
            if (preg_match($pattern, $origin)) {
                return true;
            }
        }

        return false;
    }

    protected function hasMatchingPath(Request $request)
    {
        $url = $request->pathInfo();
        $url = trim($url, '/');
        if ($url === '') {
            $url = '/';
        }

        $paths = $this->getPathsByHost($request->host(true));

        foreach ($paths as $path) {
            if ($path !== '/') {
                $path = trim($path, '/');
            }

            if ($path === $url) {
                return true;
            }

            $pattern = $this->convertWildcardToPattern($path);

            if (preg_match($pattern, $url) === 1) {
                return true;
            }
        }

        return false;
    }

    protected function getPathsByHost($host)
    {
        $paths = $this->paths;

        if (isset($paths[$host])) {
            return $paths[$host];
        }

        return array_filter($paths, function ($path) {
            return is_string($path);
        });
    }

    protected function convertWildcardToPattern($pattern)
    {
        $pattern = preg_quote($pattern, '#');
        $pattern = str_replace('\*', '.*', $pattern);
        return '#^' . $pattern . '\z#u';
    }
}
