<?php

namespace Core\Security;

use Core\Security\Random;
use Core\Security\Response;

class Csrf
{
    public static function validateToken(string|null $keyName = '_csrf_token_key', string|null $hashName = '_csrf_token_hash', bool $csrf_enabled = CSRF_ENABLED)
    {
        $hash = $GLOBALS[$hashName];
        $key = $GLOBALS[$keyName];

        unset($GLOBALS[$hashName]);
        unset($GLOBALS[$keyName]);

        if (!$csrf_enabled) return false;
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') return false;
        if (!isset($_POST['_csrf_token'])) Response::terminateNoAuth();
        if (!$hash || !$key) Response::terminateUnuth();

        $token = $_POST['_csrf_token'];
        unset($_POST['_csrf_token']);

        if (self::hashToken($token, $key) !== $hash) Response::terminateUnuth();
    }

    public static function generateToken(bool $csrf_enabled = CSRF_ENABLED)
    {
        if (!$csrf_enabled) return null;

        $key = Random::key();
        $token = Random::hex();
        $hash = self::hashToken($token, $key);

        $_SESSION['hash'] = $hash;
        $_SESSION['key'] = $key;

        return $token;
    }

    public static function hashToken(string $token, string $key)
    {
        return hash('sha256', $token, $key);
    }
}
