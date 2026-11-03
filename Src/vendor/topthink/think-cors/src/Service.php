<?php

namespace think\cors;

class Service extends \think\Service
{
    public function boot(): void
    {
        $this->app->event->listen('HttpRun', function () {
            $this->app->middleware->add(HandleCors::class);
        });
    }
}
