<?php

namespace Core\Base;

use Core\Security\Decryptor;
use Core\Security\Encryptor;

class Session
{
    private static function key(string $name): string
    {
        return hash('sha256', $name . APP_KEY);
    }

    public static function set(string $name, mixed $data): void
    {
        $enc = new Encryptor();
        $enc->setKeys(APP_KEY);
        $_SESSION[self::key($name)] = $enc->encrypt($data);
    }

    public static function get(string $name)
    {
        $dec = new Decryptor();
        $dec->setKeys(APP_KEY);
        return (isset($_SESSION[self::key($name)])) ? $dec->decrypt($_SESSION[self::key($name)]) : null;
    }

    public static function unset(string $name)
    {
        $value = self::get($name);
        unset($_SESSION[self::key($name)]);
        return $value;
    }

    public static function fixedSet(
        string $name,
        mixed $data,
        string $route = REQUEST->route,
        ?RequestType $request = null
    ): void {
        $name = self::key($route) . "::$name";
        self::set($name, $data);
    }

    public static function fixedGet(string $name)
    {
        $name = self::key(REQUEST->route) . "::$name";
        return self::get($name);
    }
}
