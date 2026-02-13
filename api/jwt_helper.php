<?php

/**
 * Simple JWT implementation for pure PHP (no dependencies)
 */

class JWT
{
    private static $secret;

    private static function init()
    {
        if (!self::$secret) {
            self::$secret = $_ENV['JWT_SECRET'] ?? 'default_fallback_secret_key';
        }
    }

    public static function encode(array $payload, int $expiry = 3600): string
    {
        self::init();
        $header = json_encode(['typ' => 'JWT', 'alg' => 'HS256']);

        $payload['exp'] = time() + $expiry;
        $payload['iat'] = time();

        $base64UrlHeader = self::base64UrlEncode($header);
        $base64UrlPayload = self::base64UrlEncode(json_encode($payload));

        $signature = hash_hmac('sha256', "$base64UrlHeader.$base64UrlPayload", self::$secret, true);
        $base64UrlSignature = self::base64UrlEncode($signature);

        return "$base64UrlHeader.$base64UrlPayload.$base64UrlSignature";
    }

    public static function decode(string $token): ?array
    {
        self::init();
        $parts = explode('.', $token);
        if (count($parts) !== 3) return null;

        list($header, $payload, $signature) = $parts;

        $validSignature = hash_hmac('sha256', "$header.$payload", self::$secret, true);
        if (self::base64UrlEncode($validSignature) !== $signature) {
            return null;
        }

        $decodedPayload = json_decode(self::base64UrlDecode($payload), true);

        if (isset($decodedPayload['exp']) && $decodedPayload['exp'] < time()) {
            return null; // Expired
        }

        return $decodedPayload;
    }

    private static function base64UrlEncode($data)
    {
        return str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($data));
    }

    private static function base64UrlDecode($data)
    {
        $remainder = strlen($data) % 4;
        if ($remainder) {
            $data .= str_repeat('=', 4 - $remainder);
        }
        return base64_decode(str_replace(['-', '_'], ['+', '/'], $data));
    }
}
