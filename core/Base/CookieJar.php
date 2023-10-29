<?php

namespace Core\Base;

class CookieJar
{
    public static function set(string $name, $value, int $expiry_in_days = 30): string|false
    {
        $expiry = time() + 60 * 60 * 24 * $expiry_in_days;
        $key = hash('sha256', $name . APP_KEY);
        setcookie($key, $value, $expiry, '/' . APP_ROOT);
        return date('Y-m-d H:i:s', $expiry);
    }
    
    public static function get(string $name)
    {
        $key = hash('sha256', $name . APP_KEY);
        return (isset($_COOKIE[$key])) ? $_COOKIE[$key] : null;
    }

    public static function unset(string $name)
    {
        $key = hash('sha256', $name . APP_KEY);
        unset($_COOKIE[$key]);
    }
}
