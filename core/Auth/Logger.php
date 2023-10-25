<?php

namespace Core\Auth;

use Core\Base\Request;
use DateTime;

class Logger
{
    private string $KEY; // Session key for integrity check

    public function __construct(
        private AuthTable $auth_table, // The authentication table
        private bool $hash_pass = true, // Whether to hash passwords
        private string $hash_algo = 'sha256', // Password hashing algorithm
        private ?string $identifier_regex = null, // Regular expression for identifier validation
        private ?string $passkey_regex = null, // Regular expression for passkey validation
    ) {
        $this->KEY = hash($this->hash_algo, APP_KEY); // Initialize the session integrity key
    }

    public function get_val($key): null|bool|string
    {
        if (!$this->is_logged_in()) return false; // Check if the user is logged in

        // Generate a hashed session key for the provided key
        $hashed_key = hash($this->hash_algo, $key . APP_KEY);

        if (!isset($_SESSION[$hashed_key])) return null; // Check if the session key exists

        return $_SESSION[$hashed_key]; // Return the value associated with the session key
    }

    private function save_to_session(array $userData): bool
    {
        if (session_regenerate_id()) {
            foreach ($userData as $key => $value) {
                if ($key[0] === '_') continue;
                else {
                    // Generate a hashed session key for the current user data
                    $hashed_key = hash($this->hash_algo, $key . APP_KEY);
                    $_SESSION[$hashed_key] = $value; // Store the value in the session
                }
            }
            $_SESSION[$this->KEY] = hash($this->hash_algo, $_SESSION[hash($this->hash_algo, $this->auth_table->identifier . APP_KEY)]); // Update integrity key
            return true; // Return true on successful session save
        }
        return false; // Return false if session regeneration fails
    }

    public function login(Request $Request)
    {
        if ($Request->method !== 'POST')
            return ['success' => false, 'response' => LoginResponse::INVALID_METHOD];
        if (!array_key_exists($this->auth_table->identifier, $Request->data))
            return ['success' => false, 'response' => LoginResponse::ABSENT_IDENTIFIER];
        if (!array_key_exists($this->auth_table->key, $Request->data))
            return ['success' => false, 'response' => LoginResponse::ABSENT_PASSKEY];

        $val_col = $this->auth_table->identifier;
        $passkey_col = $this->auth_table->key;
        $val = $Request->data[$val_col];
        $passkey = $Request->data[$passkey_col];

        if ($this->identifier_regex && !preg_match($this->identifier_regex, $val))
            return ['success' => false, 'response' => LoginResponse::INVALID_IDENTIFIER];
        if ($this->passkey_regex && !preg_match($this->passkey_regex, $val))
            return ['success' => false, 'response' => LoginResponse::INVALID_PASSKEY];

        $val = $this->auth_table->conn->real_escape_string($val);
        $passkey = $this->auth_table->conn->real_escape_string($passkey);
        if ($this->hash_pass) $passkey = hash($this->hash_algo, $passkey);


        $exists = $this->auth_table->entry_exists("$val_col = '$val' && $passkey_col = '$passkey'");
        if (!$exists) return ['success' => false, 'response' => LoginResponse::MISMATCHED_CREDENTIAL];

        $user = $this->auth_table->get_entry_by_key($val, $val_col);

        if ($user['types'] == 'staff' && $user['access'] == 0)
            return ['success' => false, 'response' => LoginResponse::MISMATCHED_CREDENTIAL];

        // Try to save user-related data in the session
        if (!$this->save_to_session($user))
            return ['success' => false, 'response' => LoginResponse::UNKNOWN];

        return [
            'success' => true,
            'response' => LoginResponse::SUCCESS,
            'data' => $user,
        ];
    }

    public function logout()
    {
        session_destroy(); // Destroy the session
    }

    public function is_logged_in(): mixed
    {
        $hashed_identifier_key = hash($this->hash_algo, $this->auth_table->identifier . APP_KEY);
        if (!isset($_SESSION[$this->KEY]) || !isset($_SESSION[$hashed_identifier_key])) return false;
        $tampered = !($_SESSION[$this->KEY] == hash($this->hash_algo, $_SESSION[$hashed_identifier_key]));
        if ($tampered) {
            $this->logout(); // Logout the user if session integrity is compromised
            return false;
        }
        return true; // User is logged in and session is valid
    }
}
