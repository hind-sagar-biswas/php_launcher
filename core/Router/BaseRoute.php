<?php

namespace Core\Router;


class BaseRoute
{
    public string $file;
    public string $name;

    use GaurdTrait;
    use DataTrait;
    use QueryTrait;

    public function __construct(public readonly string $method = 'GET', public readonly string $route)
    {
    }

    public function route_pattern()
    {
        return '#^' . preg_replace_callback('/{([^\/]+)}/', fn ($matches) => '(?P<' . $matches[1] . '>[^/]+)', $this->route) . '$#';
    }
}