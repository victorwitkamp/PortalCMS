<?php
/**
 * Copyright Victor Witkamp (c) 2020.
 */

declare(strict_types=1);

namespace PortalCMS\Core\Security;

use PortalCMS\Core\Config\Config;
use RuntimeException;
use function function_exists;
use function ord;

/**
 * Encryption and Decryption Class
 */
class Encryption
{
    /**
     * Cipher algorithm
     * @var string
     */
    private const CIPHER = 'aes-256-cbc';

    /**
     * Hash function
     * @var string
     */
    private const HASH_FUNCTION = 'sha256';

    /**
     * Constructor for Encryption object. This is empty and private so that this object cannot be instantiated.
     * @access private
     */
    private function __construct()
    {
    }

    /**
     * Encrypt a string.
     */
    public static function encrypt(string $plain): string
    {
        if (!function_exists('openssl_cipher_iv_length') || !function_exists('openssl_random_pseudo_bytes') || !function_exists('openssl_encrypt')) {
            throw new RuntimeException('Encryption function doesn\'t exist');
        }
        // generate initialization vector, this will make $iv different every time,
        // so, encrypted string will be also different.
        $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length(self::CIPHER));
        if (!$iv || empty($iv)) {
            throw new RuntimeException('openssl_random_pseudo_bytes returned false');
        }
        // generate key for authentication using ENCRYPTION_KEY & HMAC_SALT
        $key = mb_substr(hash(self::HASH_FUNCTION, Config::get('ENCRYPTION_KEY') . Config::get('HMAC_SALT')), 0, 32, '8bit');
        $ciphertext = $iv . openssl_encrypt($plain, self::CIPHER, $key, OPENSSL_RAW_DATA, $iv);
        // apply the HMAC
        $hmac = hash_hmac('sha256', $ciphertext, $key);
        return $hmac . $ciphertext;
    }

    /**
     * Decrypted a string.
     * @return string
     */
    public static function decrypt(string $ciphertext): ?string
    {
        if (empty($ciphertext)) {
            throw new RuntimeException('The String to decrypt can\'t be empty');
        }

        if (!function_exists('openssl_cipher_iv_length') || !function_exists('openssl_decrypt')) {
            throw new RuntimeException('Encryption function doesn\'t exist');
        }

        // generate key used for authentication using ENCRYPTION_KEY & HMAC_SALT
        $key = mb_substr(hash(self::HASH_FUNCTION, Config::get('ENCRYPTION_KEY') . Config::get('HMAC_SALT')), 0, 32, '8bit');

        // split cipher into: hmac, cipher & iv
        $macSize = 64;
        $hmac = mb_substr($ciphertext, 0, $macSize, '8bit');
        $iv_cipher = mb_substr($ciphertext, $macSize, null, '8bit');

        // generate original hmac & compare it with the one in $ciphertext
        $originalHmac = hash_hmac('sha256', $iv_cipher, $key);
        if (!self::hashEquals($hmac, $originalHmac)) {
            return null;
        }

        // split out the initialization vector and cipher
        $iv_size = openssl_cipher_iv_length(self::CIPHER);

        return openssl_decrypt(mb_substr($iv_cipher, $iv_size, null, '8bit'), self::CIPHER, $key, OPENSSL_RAW_DATA, mb_substr($iv_cipher, 0, $iv_size, '8bit'));
    }

    /**
     * A timing attack resistant comparison.
     * @access private
     * @static static method
     * @param string $hmac    The hmac from the ciphertext being decrypted.
     * @param string $compare The comparison hmac.
     * @see    https://github.com/sarciszewski/php-future/blob/bd6c91fb924b2b35a3e4f4074a642868bd051baf/src/Security.php#L36
     */
    private static function hashEquals(string $hmac, string $compare): bool
    {
        if (function_exists('hash_equals')) {
            return hash_equals($hmac, $compare);
        }

        // if hash_equals() is not available,
        // then use the following snippet.
        // It's equivalent to hash_equals() in PHP 5.6.
        $hashLength = mb_strlen($hmac, '8bit');
        $compareLength = mb_strlen($compare, '8bit');

        if ($hashLength !== $compareLength) {
            return false;
        }

        $result = 0;
        for ($i = 0; $i < $hashLength; $i++) {
            $result |= (ord($hmac[$i]) ^ ord($compare[$i]));
        }

        return $result === 0;
    }
}
