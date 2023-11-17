<?php

namespace Core\Router;

use Core\Base\Request;
use Core\Security\Response;

class ApiRouter extends RouteHandler
{
    public function route(Request $Request)
    {
        $path = null;
        header("Content-Type: application/json");

        // LOAD EXPORTED VARIABLES
        if (isset($GLOBALS['__exported'])) {
            $variableNames = explode('|', $GLOBALS['__exported']);
            foreach ($variableNames as $var) {
                $$var = $GLOBALS[$var];
            }
        }

        if ($this->route_exists($Request->route, $Request->method)) {
            define('CURRENT_ROUTE', $this->routesByRoute[$Request->method][$Request->route]);
            $path = CURRENT_ROUTE->file;

            if (!empty(CURRENT_ROUTE->gaurds)) {
                require_once ROOTPATH . 'shell/gaurds/api.php';
                foreach (CURRENT_ROUTE->gaurds as $gaurd) {
                    $gaurdName = 'gaurd_' . $gaurd;
                    if (function_exists($gaurdName)) {
                        [$errorCode, $message] = call_user_func($gaurdName, true);
                        if ($errorCode !== null) {
                            Response::code($errorCode, $message);
                            $path = false;
                        }
                    } else throw new \Exception("Gaurd `$gaurd` not defined!");
                }
            }

            if (!empty(CURRENT_ROUTE->data_requires)) {
                $missing = [];
                foreach (CURRENT_ROUTE->data_requires as $data_key) {
                    if (array_key_exists($data_key, $Request->data)) continue;
                    $missing[] = $data_key;
                }

                if (!empty($missing)) {
                    Response::code(
                        412,
                        'Preconditions for this request not Met! Any/All/Some required Data missing.',
                        [
                            'required' => CURRENT_ROUTE->data_requires,
                            'missing' => $missing,
                        ]
                    );
                }
            }

            if (!empty(CURRENT_ROUTE->query_requires)) {
                $missing = [];
                foreach (CURRENT_ROUTE->query_requires as $query_key) {
                    if (array_key_exists($query_key, $Request->query)) continue;
                    $missing[] = $query_key;
                }

                if (!empty($missing)) {
                    Response::code(
                        412,
                        'Preconditions for this request not Met! Any/All/Some required Query Params missing.',
                        [
                            'required' => CURRENT_ROUTE->query_requires,
                            'missing' => $missing,
                        ]
                    );
                }
            }
        }

        if ($path === null) {
            Response::code(404, 'Requested API route not found', (APP_DEBUG) ? $Request : null);
        } elseif ($path) {
            require $path;
        }
    }
}
