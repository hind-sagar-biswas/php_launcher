<?php

namespace Core\Router;


class RouteHandler
{
    protected array $routesByName = [
        'GET' => [],
        'POST' => [],
    ];
    protected array $routesByRoute = [
        'GET' => [],
        'POST' => [],
    ];

    public function add_get_route(BaseRoute $route)
    {
        $this->routesByName['GET'][$route->name] = $route;
        $this->routesByRoute['GET'][$route->route] = $route;
    }

    public function add_post_route(BaseRoute $route)
    {
        $this->routesByName['POST'][$route->name] = $route;
        $this->routesByRoute['POST'][$route->route] = $route;
    }

    public function get_route(string $value, bool $byName = true, $method = 'GET'): string
    {
        if ($byName) {
            if (!$this->name_exists($value, $method))
                throw new \Exception("Requested named $method route `$value` does not exists");
            return $this->routesByName[$method][$value]->route;
        }
        if (!$this->route_exists($value, $method))
            throw new \Exception("Requested $method route `$value` does not exists");
        return $this->routesByRoute[$method][$value]->name;
    }


    public function route_exists(string $route, string $method = 'GET'): bool
    {
        return array_key_exists($route, $this->routesByRoute[$method]);
    }

    public function name_exists(string $name, string $method = 'GET'): bool
    {
        return array_key_exists($name, $this->routesByName[$method]);
    }
}
