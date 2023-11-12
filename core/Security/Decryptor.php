<?php

namespace Core\Security;

use InvalidArgumentException;

class Decryptor
{
    private ?string $key;
    public string $cipher = "aes-256-cbc";

    public function __construct(public readonly EncryptionType $type = EncryptionType::SINGLE_KEY)
    {
    }

    public function setKeys(string $key): void
    {
        if (empty($key)) throw new InvalidArgumentException("Key cannot be empty");

        if ($this->type === EncryptionType::SINGLE_KEY) $this->key = $key;
        else {
            $private_key = openssl_pkey_get_private($key);
            if (!$private_key) throw new InvalidArgumentException("Provided private key is not a PEM key");
            $this->key = $private_key;
        }
    }

    public function getKey(): string
    {
        return $this->key;
    }

    public function changeCipher(string $cipher): void
    {
        $this->cipher = $cipher;
    }

    public function decrypt(string $encrypted_data, bool $decode_json = true): mixed
    {
        if (empty($encrypted_data)) throw new InvalidArgumentException("Encrypted data cannot be empty");

        $decrypted_data = null;
        $encrypted_data = base64_decode($encrypted_data);

        if ($encrypted_data === false) throw new \Exception("Encrypted data is not base64 encoded");
        
        if ($this->type === EncryptionType::DUAL_KEY) openssl_private_decrypt($encrypted_data, $decrypted_data, $this->key);
        else {
            $ivlen = openssl_cipher_iv_length($this->cipher);
            $iv = substr($encrypted_data, 0, $ivlen);
            $data = substr($encrypted_data, $ivlen);
            $decrypted_data = openssl_decrypt($data, $this->cipher, $this->key, 0, $iv);
        }

        if ($decrypted_data === false) throw new \Exception("Decryption failed");
        return ($decode_json) ? json_decode($decrypted_data) : $decrypted_data;
    }
}
