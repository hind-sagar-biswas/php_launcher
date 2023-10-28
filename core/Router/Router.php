<?php

namespace Core\Router;

use Core\Base\Request;
use Core\Security\Csrf;
use Core\Base\RequestType;

class Router
{
    protected FileRouter|WebRouter|ApiRouter $router;

    public function __construct(protected readonly RouteSystem $system)
    {
        if ($this->system === RouteSystem::RAW) {
            $this->router = new FileRouter();
        } else {
            $this->router = (REQUEST->type === RequestType::WEB) ? new WebRouter() : new ApiRouter();
        }
        define('CSRF_TOKEN', (REQUEST->type === RequestType::WEB) ? Csrf::generateToken() : null);
    }

    public function route(Request $request)
    {
        $this->router->route($request);
    }

    public function add_routes(WebRoute|ApiRoute ...$routes)
    {
        foreach ($routes as $route) {
            if ($route->method == 'GET') $this->router->add_get_route($route);
            elseif ($route->method == 'POST') $this->router->add_post_route($route);
        }
    }

    public function getName(string $route): string
    {
        return $this->router->get_route($route, true);
    }

    public function postName(string $route): string
    {
        return $this->router->get_route($route, false, 'POST');
    }

    public function getRoute(string $name, bool $full = true): string
    {
        if ($full) return APP_URL . $this->router->get_route($name);
        return $this->router->get_route($name);
    }

    public function postRoute(string $name, bool $full = true): string
    {
        if ($full) return APP_URL . $this->router->get_route($name, method: 'POST');
        return $this->router->get_route($name, method: 'POST');
    }

    public static function get(string $route): WebRoute|ApiRoute
    {
        return (REQUEST->type === RequestType::WEB) ? new WebRoute('GET', $route) : new ApiRoute('GET', $route);
    }

    public static function post(string $route): WebRoute|ApiRoute
    {
        return (REQUEST->type === RequestType::WEB) ? new WebRoute('POST', $route) : new ApiRoute('POST', $route);
    }
}
