<?php

namespace Core\Base;

use Core\Security\Decryptor;
use Core\Security\Encryptor;

class CookieJar
{
    public static function set(string $name, $value, int $expiry_in_days = 30): string|false
    {
        $enc = new Encryptor();
        $enc->setKeys(APP_KEY);
        
        $expiry = time() + 60 * 60 * 24 * $expiry_in_days;
        $key = hash('sha256', $name . APP_KEY);
        setcookie($key, $enc->encrypt($value), $expiry, '/' . APP_ROOT);
        return date('Y-m-d H:i:s', $expiry);
    }
    
    public static function get(string $name)
    {
        $dec = new Decryptor();
        $dec->setKeys(APP_KEY);

        $key = hash('sha256', $name . APP_KEY);
        return (isset($_COOKIE[$key])) ? $dec->decrypt($_COOKIE[$key]) : null;
    }

    public static function unset(string $name)
    {
        $key = hash('sha256', $name . APP_KEY);
        unset($_COOKIE[$key]);
    }
}
