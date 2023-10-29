<?php

namespace Core\Security;

use Core\Security\Random;
use Core\Security\Response;

class Csrf
{
    // Validate a CSRF token
    public static function validateToken(string|null $keyName = '_csrf_token_key', string|null $hashName = '_csrf_token_hash', bool $csrf_enabled = CSRF_ENABLED)
    {
        // Get the CSRF hash and key from global variables
        $hash = $GLOBALS[$hashName];
        $key = $GLOBALS[$keyName];

        // Remove the global variables to prevent further access
        unset($GLOBALS[$hashName]);
        unset($GLOBALS[$keyName]);

        // If CSRF protection is disabled, return false
        if (!$csrf_enabled) return false;
        // If the HTTP request method is not POST, return false
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') return false;
        // If the '_csrf_token' is not present in the POST data, terminate the request
        if (!isset($_POST['_csrf_token'])) Response::terminateNoAuth();
        // If the hash or key is missing, terminate the request
        if (!$hash || !$key) Response::terminateUnuth();

        // Get the token from POST data and remove it
        $token = $_POST['_csrf_token'];
        unset($_POST['_csrf_token']);

        // Verify that the received token matches the expected hash
        if (self::hashToken($token, $key) !== $hash) Response::terminateUnuth();
    }

    // Generate a new CSRF token
    public static function generateToken(bool $csrf_enabled = CSRF_ENABLED)
    {
        // If CSRF protection is disabled, return null
        if (!$csrf_enabled) return null;

        // Generate a key and a random token
        $key = Random::key();
        $token = Random::hex();
        // Compute the hash of the token using the key
        $hash = self::hashToken($token, $key);

        // Store the hash and key in the session
        $_SESSION['hash'] = $hash;
        $_SESSION['key'] = $key;

        // Return the generated token
        return $token;
    }

    // Hash a token using a key
    public static function hashToken(string $token, string $key)
    {
        // Hash the token using SHA-256 and the key
        return hash('sha256', $token, $key);
    }
}
