<?php

namespace Core\Router;

use Core\Base\Request;
use Core\Security\Response;

class WebRouter extends RouteHandler
{
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
