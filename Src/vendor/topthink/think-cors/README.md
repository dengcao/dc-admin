# ThinkCors

ThinkPHP跨域扩展

## 安装

```
composer require topthink/think-cors
```

## 配置

配置文件位于 `config/cors.php`

```
[
    'paths' => ['api/*'],
    ...
]
```

### paths 配置示例

允许 api 目录下的跨域请求，`*` 代表通配符。

```php
[
    'paths' => ['api/*']
]
```

当项目有多个域名时，支持为不同域名配置不同的目录。

```php
[
    'paths' => [
        'www.thinkphp.cn' => ['api/*'],
        'doc.thinkphp.cn' => ['user/*', 'article/*'],
    ]
]
```

### allowed_origins 配置示例

当配置中有 `*` 时，代表不限制来源域。

```php
[
    'allowed_origins' => ['*'],
]
```

当我们需要限制来源域时，可以这么写。

```php
[
    'allowed_origins' => ['www.thinkphp.cn', 'm.thinkphp.cn'],
]
```

### allowed_origins_patterns 配置示例

除了固定来源域，有时候我们还想要允许不固定但有规则的来源域，那么可以通过正则来实现。例如这里我们允许 `thinkphp.cn` 的所有二级域。

```php
[
    'allowed_origins_patterns' => ['#.*\.thinkphp\.cn#'],
]
```

### allowed_methods 配置示例

当配置中有 `*` 时，代表不限制来源请求方式。

```php
[
    'allowed_methods' => ['*'],
]
```

当然我们也可以限制只允许 `GET` 和 `POST` 的跨域请求。

```php
[
    'allowed_methods' => ['GET', 'POST'],
]
```

### allowed_headers 配置示例

当配置中有 `*` 时，代表不限制请求头。

```php
[
    'allowed_headers' => ['*'],
]
```

当然我们也可以只允许跨域请求只传递给我们部分请求头。

```php
[
    'allowed_headers' => ['X-Custom-Header', 'Upgrade-Insecure-Requests'],
]
```

### max_age 配置示例

跨域预检结果是有缓存的，如果值为 -1，表示禁用缓存，则每次请求前都需要使用 `OPTIONS` 预检请求。如果想减少 `OPTIONS` 预检请求，我们可以把缓存有效期设置长些。
列如这里，我们把有效期设置为 2 小时（7200 秒）：

```php
[
    'max_age' => 7200,
]
```

### supports_credentials 配置示例

`Credentials` 可以是 `cookies`、`authorization headers` 或 `TLS client certificates`。当接口需要这些信息时，开启该项配置后，相关请求将会携带 `Credentials` 信息（如果有的话）。

```php
[
    'supports_credentials' => true,
]
```

