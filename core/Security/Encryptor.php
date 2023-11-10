<?php

namespace Core\Security;

use Core\Security\Random;

class Encryptor
{
    private ?string $key;
    public string $cipher = "aes-256-cbc";

    public function __construct(public readonly EncryptionType $type)
    {
    }

    public function generate(): array|string
    {
        return ($this->type === EncryptionType::DUAL_KEY) ? Random::encryptionKeys() : Random::hex();
    }

    public function setKeys(string $key): void
    {
        $this->key = $key;
    }

    public function getKey(): string
    {
        return $this->key;
    }

    public function changeCipher($cipher)
    {
        $this->cipher = $cipher;
    }

    public function encrypt($data)
    {
        $data = json_encode($data);

        if ($this->type === EncryptionType::DUAL_KEY) {
            openssl_public_encrypt($data, $encrypted_data, $this->key);
            return $encrypted_data;
        }

        $ivlen = openssl_cipher_iv_length($this->cipher);
        $iv = openssl_random_pseudo_bytes($ivlen);
        $encrypted_data = openssl_encrypt($data, $this->cipher, $this->key, 0, $iv);
        return base64_encode($iv . $encrypted_data);
    }
}
