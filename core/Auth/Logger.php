<?php

namespace Core\Auth;

use Core\Base\CookieJar;
use Core\Base\Request;
use Core\Base\Session;

class Logger
{
    public function __construct(
        public readonly AuthTable $auth_table, // The authentication table
        private ?TokenTable $token_table = null, // The token table [to enable remember me feature]
        private bool $hash_pass = true, // Whether to hash passwords
        private string $hash_algo = 'sha256', // Password hashing algorithm
        private ?string $identifier_regex = null, // Regular expression for identifier validation
        private ?string $passkey_regex = null, // Regular expression for passkey validation
    ) {}

    public function get_val($key): null|bool|string
    {
        if (!$this->is_logged_in()) return false; // Check if the user is logged in

        $pk = $this->auth_table->table->get_pk();

        if ($key == $pk) return Session::get("auth_$pk");
        if (!$this->auth_table->table->hasColumn($key)) return null;
        return $this->auth_table->get_entry_by_key(Session::get("auth_" . $pk), $pk, [$key])[$key];
    }

    private function save_to_session(array $userData): bool
    {
        if (session_regenerate_id()) {
            $pk = $this->auth_table->table->get_pk();
            $key = "auth_$pk";
            Session::set($key, $userData[$pk]); // Store the PK value in the session
            return true; // Return true on successful session save
        }
        return false; // Return false if session regeneration fails
    }

    public function login(Request $Request): array
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
        if ($this->passkey_regex && !preg_match($this->passkey_regex, $passkey))
            return ['success' => false, 'response' => LoginResponse::INVALID_PASSKEY];

        $val = $this->auth_table->conn->real_escape_string($val);
        $passkey = $this->auth_table->conn->real_escape_string($passkey);
        if ($this->hash_pass) $passkey = hash($this->hash_algo, $passkey);


        $exists = $this->auth_table->entry_exists("$val_col = '$val' && $passkey_col = '$passkey'");
        if (!$exists) return ['success' => false, 'response' => LoginResponse::MISMATCHED_CREDENTIAL];

        $user = $this->auth_table->get_entry_by_key($val, $val_col);

        // Try to save user-related data in the session
        if (!$this->save_to_session($user))
            return ['success' => false, 'response' => LoginResponse::UNKNOWN];

        if ($this->token_table && isset($Request->data['_remember_me'])) {
            $this->remember_me($user[$this->auth_table->table->get_pk()]);
        }

        $user = array_filter($user, fn ($k): bool => ($k !== $this->auth_table->key), ARRAY_FILTER_USE_KEY);

        return [
            'success' => true,
            'response' => LoginResponse::SUCCESS,
            'data' => $user,
        ];
    }

    public function signup(Request $Request, array $custom_data = [], bool $repass = true, bool $login = true): array
    {
        $data = $Request->data;
        $identifier = $this->auth_table->identifier;
        $key = $this->auth_table->key;

        if ($Request->method !== 'POST')
            return ['success' => false, 'response' => SignupResponse::INVALID_METHOD];
        if (!array_key_exists($identifier, $data))
            return ['success' => false, 'response' => SignupResponse::ABSENT_IDENTIFIER];
        if (!array_key_exists($key, $data))
            return ['success' => false, 'response' => SignupResponse::ABSENT_PASSKEY];

        $passkey = $data[$key];
        $data[$identifier] = trim($data[$identifier]);
        if ($this->hash_pass) $data[$key] = hash($this->hash_algo, $passkey);

        if ($repass && !array_key_exists("re_$key", $data))
            return ['success' => false, 'response' => SignupResponse::ABSENT_RE_PASSKEY];
        if ($repass && $data["re_$key"] !== $passkey)
            return ['success' => false, 'response' => SignupResponse::MISSMATCHED_PASSKEY];
        if ($this->auth_table->entry_exists("$identifier = \"" . $data[$identifier] . '"'))
            return ['success' => false, 'response' => SignupResponse::PREEXISTING_IDENTIFIER];
        if ($this->identifier_regex && !preg_match($this->identifier_regex, $identifier))
            return ['success' => false, 'response' => SignupResponse::INVALID_IDENTIFIER];
        if ($this->passkey_regex && !preg_match($this->passkey_regex, $key))
            return ['success' => false, 'response' => SignupResponse::INVALID_PASSKEY];


        foreach ($custom_data as $k => $value) {
            if ($k === $identifier || $k === $key) continue;
            $data[$k] = $value;
        }

        if ($user = $this->auth_table->insert($data)) {
            if ($login && !$this->is_logged_in()) {
                $login_response = $this->login($Request);
                if ($login_response['success']) return $login_response;
                return  ['success' => false, 'response' => SignupResponse::LOGIN_FAILED];
            }
            return [
                'success' => true,
                'response' => SignupResponse::SUCCESS,
                'data' => $user,
            ];
        }
        return ['success' => false, 'response' => SignupResponse::UNKNOWN];
    }

    public function logout()
    {
        // remove the remember_me cookie if enables
        if ($this->token_table) {
            $this->delete_token($this->get_val($this->auth_table->table->get_pk()));
            CookieJar::unset('_remember_me');
        }
        session_destroy(); // Destroy the session
    }

    public function is_logged_in(): mixed
    {
        $pk = "auth_" . $this->auth_table->table->get_pk();
        if (!Session::get($pk)) {
            if (!$this->token_table) return false;

            $token = CookieJar::get('_remember_me');
            if (!$token || !$this->validate_token($token)) return false;

            $data = $this->find_user_by_token($token);
            if (!$data) return false;

            $this->save_to_session($data);
            return $this->is_logged_in();
        }
        return true; // User is logged in and session is valid
    }

    protected function remember_me(int $foreign_target_value, int $day = 30): void
    {
        [$selector, $validator, $token] = $this->generate_tokens();

        $this->delete_token($foreign_target_value);
        $expiry = CookieJar::set('_remember_me', $token);
        if ($expiry) $this->create_token($foreign_target_value, $selector, $validator, $expiry);
    }

    //  Token Management Sections
    protected function generate_tokens(): array
    {
        $selector = bin2hex(random_bytes(16));
        $validator = bin2hex(random_bytes(32));
        return [$selector, $validator, $selector . ':' . $validator];
    }

    protected function parse_token(string $token): ?array
    {
        $parts = explode(':', $token);
        if ($parts && count($parts) == 2) return [$parts[0], $parts[1]];
        return null;
    }

    protected function validate_token($token)
    {
        [$selector, $validator] = $this->parse_token($token);
        $tokens = $this->find_user_token_by_selector($selector);

        if (!$tokens) return false;
        if (hash('sha256', $validator) != $tokens['hashed_validator']) return false;

        return $tokens;
    }

    protected function find_user_token_by_selector(string $selector)
    {
        $selector = mysqli_real_escape_string($this->token_table->conn, $selector);
        $condition = $this->token_table->selector . " = \"$selector\" && " . $this->token_table->expiry . " >= NOW()";
        return $this->token_table->get_entry_by_condition($condition);
    }

    protected function find_user_by_token(string $token)
    {
        [$selector, $validator] = $this->parse_token($token);
        $selector = mysqli_real_escape_string($this->token_table->conn, $selector);
        $condition = $this->token_table->selector . " = \"$selector\" && " . $this->token_table->expiry . " >= NOW()";

        $target = $this->token_table->get_entry_by_condition($condition, [$this->token_table->foreign_target]);
        if (!$target) return false;

        $auth_key = $target[$this->token_table->foreign_target];
        return $this->auth_table->get_entry_by_key($auth_key, $this->auth_table->table->get_pk());
    }

    protected function create_token(string|int $foreign_target_value, string $selector, string $validator, string $expiry)
    {
        $data = [
            $this->token_table->foreign_target => $foreign_target_value,
            $this->token_table->selector => $selector,
            $this->token_table->validator => hash('sha256', $validator),
            $this->token_table->expiry => $expiry,
        ];
        $result = $this->token_table->insert($data);

        return ($result);
    }

    protected function delete_token(string|int $foreign_target_value): bool
    {
        return $this->token_table->delete($this->token_table->foreign_target . " = \"$foreign_target_value\"");
    }
}
