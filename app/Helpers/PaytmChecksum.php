<?php

namespace App\Helpers;

class PaytmChecksum
{
    /**
     * Generate signature for Paytm request
     *
     * @param string $params
     * @param string $key
     * @return string
     */
    public static function generateSignature(string $params, string $key): string
    {
        return base64_encode(hash_hmac('sha256', $params, $key, true));
    }

    /**
     * Verify signature from Paytm response
     *
     * @param string $params
     * @param string $key
     * @param string $checksum
     * @return bool
     */
    public static function verifySignature(string $params, string $key, string $checksum): bool
    {
        $generatedChecksum = self::generateSignature($params, $key);
        return hash_equals($generatedChecksum, $checksum);
    }

    /**
     * Generate checksum by params and key
     *
     * @param array $params
     * @param string $key
     * @param string $salt
     * @return string
     */
    public static function getChecksumFromArray(array $params, string $key, string $salt = ''): string
    {
        ksort($params);
        $paramsString = self::getStringByParams($params);
        return self::generateSignature($paramsString . $salt, $key);
    }

    /**
     * Verify checksum from array
     *
     * @param array $params
     * @param string $key
     * @param string $checksum
     * @return bool
     */
    public static function verifyChecksumFromArray(array $params, string $key, string $checksum): bool
    {
        unset($params['CHECKSUMHASH']);
        ksort($params);
        $paramsString = self::getStringByParams($params);
        return self::verifySignature($paramsString, $key, $checksum);
    }

    /**
     * Get string from params array
     *
     * @param array $params
     * @return string
     */
    private static function getStringByParams(array $params): string
    {
        $string = '';
        foreach ($params as $key => $value) {
            if ($key !== 'CHECKSUMHASH') {
                if (is_array($value)) {
                    $string .= self::getStringByParams($value);
                } else {
                    $string .= $value . '|';
                }
            }
        }
        return rtrim($string, '|');
    }

    /**
     * Encrypt data
     *
     * @param string $input
     * @param string $key
     * @return string
     */
    public static function encrypt(string $input, string $key): string
    {
        $key = html_entity_decode($key);
        $iv = '@@@@&&&&####$$$$';
        $encrypted = openssl_encrypt($input, 'AES-128-CBC', $key, 0, $iv);
        return $encrypted;
    }

    /**
     * Decrypt data
     *
     * @param string $encrypted
     * @param string $key
     * @return string
     */
    public static function decrypt(string $encrypted, string $key): string
    {
        $key = html_entity_decode($key);
        $iv = '@@@@&&&&####$$$$';
        $decrypted = openssl_decrypt($encrypted, 'AES-128-CBC', $key, 0, $iv);
        return $decrypted;
    }
}

