<?php

namespace Core\Router;

use Core\Base\Request;
use Core\Security\Response;

class FileRouter
{
    static public function route(Request $Request)
    {
        // LOAD EXPORTED VARIABLES
        if (isset($GLOBALS['__exported'])) {
            $variableNames = explode('|', $GLOBALS['__exported']);
            foreach ($variableNames as $var) {
                $$var = $GLOBALS[$var];
            }
        }

        $path = null;
        $extensions = ['.php', '.html'];

        foreach ($extensions as $ext) {
            $file = ($Request->route === '/') ? 'index' . $ext : substr($Request->route, 0, -1) . $ext;
            $path_to_file = ROOTPATH . $Request->type->value . '/' . $file;

            if (file_exists($path_to_file)) {
                $path = $path_to_file;
                break;
            }
        }

        ob_start();
        if (!$path) {
            if (APP_DEBUG) d($Request);
            Response::terminateNotFound();
        } else {
            require $path;
        }
        $body_content = ob_get_clean();
        ob_end_clean();

        echo $body_content;
    }
}
