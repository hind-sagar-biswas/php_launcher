<?php

namespace Core\Security;

use Core\Base\RequestType;

class Response
{

    private static $errorPages = [
        401 => '401.php',
        403 => '403.php',
        404 => '404.php',
        412 => '412.php',
    ];

    public static function code(int $code, ?string $msg = null, mixed $response = null)
    {
        http_response_code($code);
        header("Title: " .  APP_NAME);
        try {
            if (defined('REQUEST') && REQUEST->type === RequestType::API) {
                self::json($response, true, $msg);
            } else {
                header("Content-Type: text/html; charset=utf-8");
                echo '<meta name="viewport" content="width=device-width, initial-scale=1.0">';
                require ERR_PAGES . self::$errorPages[$code];
            }
        } catch (\Throwable $th) {
            header("Content-Type: text/html; charset=utf-8");
            require ERR_PAGES . self::$errorPages[$code];
        }
    }

    public static function json(mixed $data, bool $error = false, ?string $message = null)
    {
        echo json_encode([
            "error" => $error,
            "data" => $data,
            "message" => $message,
        ], JSON_PRETTY_PRINT);
    }

    public static function terminateUnuth()
    {
        self::code(401);
        die();
    }

    public static function terminateNoAuth()
    {
        self::code(403);
        die();
    }

    public static function terminateNotFound()
    {
        self::code(404);
        die();
    }

    public static function terminatePreconditionsFailed()
    {
        self::code(412);
        die();
    }

    public static function redirect($destination, string $message = '')
    {
        $_SESSION['message'] = $message;
        header("Location: " .  APP_URL . '/' . $destination);
    }
}
