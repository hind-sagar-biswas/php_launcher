<?php

namespace Core\Router;

use Core\Base\Request;
use Core\Security\Response;

class WebRouter
{
    protected array $routesByName = [
        'GET' => [],
        'POST' => [],
    ];
    protected array $routesByRoute = [
        'GET' => [],
        'POST' => [],
    ];

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

    public function add_get_route(WebRoute $route)
    {
        $this->routesByName['GET'][$route->name] = $route;
        $this->routesByRoute['GET'][$route->route] = $route;
    }

    public function add_post_route(WebRoute $route)
    {
        $this->routesByName['POST'][$route->name] = $route;
        $this->routesByRoute['POST'][$route->route] = $route;
    }

    public function route_exists(string $route, string $method = 'GET'): bool
    {
        return array_key_exists($route, $this->routesByRoute[$method]);
    }

    public function name_exists(string $name, string $method = 'GET'): bool
    {
        return array_key_exists($name, $this->routesByName[$method]);
    }

    public function route(Request $Request)
    {
        $path = null;

        // LOAD EXPORTED VARIABLES
        if (isset($GLOBALS['__exported'])) {
            $variableNames = explode('|', $GLOBALS['__exported']);
            foreach ($variableNames as $var) {
                global $$var;
            }
        }

        if ($this->route_exists($Request->route, $Request->method)) {
            define('CURRENT_ROUTE', $this->routesByRoute[$Request->method][$Request->route]);
            $path = CURRENT_ROUTE->file;

            if (!empty(CURRENT_ROUTE->gaurds)) {
                require_once ROOTPATH . 'shell/gaurds/web.php';
                foreach (CURRENT_ROUTE->gaurds as $gaurd) {
                    $gaurdName = 'gaurd_' . $gaurd;
                    if (function_exists($gaurdName)) {
                        [$redirect, $message] = call_user_func($gaurdName, true);
                        if ($redirect !== null) Response::redirect($redirect, $message);
                    } else throw new \Exception("Gaurd `$gaurd` not defined!");
                }
            }


            if (!empty(CURRENT_ROUTE->data_requires)) {
                foreach (CURRENT_ROUTE->data_requires as $data_key) {
                    if (!array_key_exists($data_key, $Request->data)) Response::terminatePreconditionsFailed();
                }
            }

            if (!empty(CURRENT_ROUTE->query_requires)) {
                foreach (CURRENT_ROUTE->query_requires as $query_key) {
                    if (!array_key_exists($query_key, $Request->query)) Response::terminatePreconditionsFailed();
                }
            }
        }

        ob_start();
        if (!$path) {
            d($Request);
            Response::terminateNotFound();
            $body_content = ob_get_clean();
        } else {
            require $path;
            $body_content = ob_get_clean();
        }
        ob_end_clean();

        echo $body_content;
    }
}
