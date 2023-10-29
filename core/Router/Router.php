<?php

namespace Core\Router;

use Core\Base\Request;
use Core\Security\Csrf;
use Core\Base\RequestType;

class Router
{
    protected FileRouter|WebRouter|ApiRouter $router;

    // Constructor for the Router class
    public function __construct(protected readonly RouteSystem $system)
    {
        // Determine the appropriate router based on the route system and request type
        if ($this->system === RouteSystem::RAW) {
            $this->router = new FileRouter();
        } else {
            $this->router = (REQUEST->type === RequestType::WEB) ? new WebRouter() : new ApiRouter();
        }
        // Generate and define a CSRF token if it's a web request
        define('CSRF_TOKEN', (REQUEST->type === RequestType::WEB) ? Csrf::generateToken() : null);
    }

    // Route a request
    public function route(Request $request)
    {
        $this->router->route($request);
    }

    // Add route objects to the router
    public function add_routes(WebRoute|ApiRoute ...$routes)
    {
        foreach ($routes as $route) {
            if ($route->method == 'GET') $this->router->add_get_route($route);
            elseif ($route->method == 'POST') $this->router->add_post_route($route);
        }
    }

    // Get the name of a route
    public function getName(string $route): string
    {
        return $this->router->get_route($route, true);
    }

    // Get the name of a POST route
    public function postName(string $route): string
    {
        return $this->router->get_route($route, false, 'POST');
    }

    // Get the route URL by name
    public function getRoute(string $name, bool $full = true): string
    {
        if ($full) return APP_URL . $this->router->get_route($name);
        return $this->router->get_route($name);
    }

    // Get the POST route URL by name
    public function postRoute(string $name, bool $full = true): string
    {
        if ($full) return APP_URL . $this->router->get_route($name, method: 'POST');
        return $this->router->get_route($name, method: 'POST');
    }

    // Create a new GET route object
    public static function get(string $route): WebRoute|ApiRoute
    {
        return (REQUEST->type === RequestType::WEB) ? new WebRoute('GET', $route) : new ApiRoute('GET', $route);
    }

    // Create a new POST route object
    public static function post(string $route): WebRoute|ApiRoute
    {
        return (REQUEST->type === RequestType::WEB) ? new WebRoute('POST', $route) : new ApiRoute('POST', $route);
    }
}
