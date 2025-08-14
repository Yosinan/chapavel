<?php

namespace Yosinan\Chapavel;

class WebhookVerifier
{
    // Docs: Chapa sends `Chapa-Signature` or `x-chapa-signature` as HMAC SHA256 of the raw JSON using your secret key.
    // Accept either header. :contentReference[oaicite:1]{index=1}
    public static function isValid(string $rawBody, ?string $sig1, ?string $sig2, string $secret): bool
    {
        $expected = hash_hmac('sha256', $rawBody, $secret);
        if ($sig1 && hash_equals($expected, $sig1)) return true;
        if ($sig2 && hash_equals($expected, $sig2)) return true;
        return false;
    }
}
