<?php

namespace App\Services;

class CustomEncryption
{
    private $encryptionKey;
    private $encryptionMethod = 'aes-256-cbc';
    
    public function __construct()
    {
        $this->encryptionKey = base64_decode(explode(':', env('CUSTOM_ENCRYPTION_KEY'))[1] ?? '');
        
        if (empty($this->encryptionKey)) {
            throw new \RuntimeException('Invalid encryption key configuration');
        }
    }

    public function encrypt($data)
    {
        $iv = random_bytes(openssl_cipher_iv_length($this->encryptionMethod));
        $encrypted = openssl_encrypt(
            $data,
            $this->encryptionMethod,
            $this->encryptionKey,
            0,
            $iv
        );
        
        if ($encrypted === false) {
            throw new \RuntimeException('Encryption failed: '.openssl_error_string());
        }
        
        return base64_encode($iv.$encrypted);
    }

    public function decrypt($data)
    {
        $raw = base64_decode($data);
        if ($raw === false) {
            return false;
        }
        
        $ivLength = openssl_cipher_iv_length($this->encryptionMethod);
        $iv = substr($raw, 0, $ivLength);
        $encrypted = substr($raw, $ivLength);
        
        return openssl_decrypt(
            $encrypted,
            $this->encryptionMethod,
            $this->encryptionKey,
            0,
            $iv
        );
    }
}