<?php

/**
 * Encodes data to a JWT.
 *
 * @param array $payload The data to encode in the token.
 * @param string $secret The secret key to sign the token.
 * @param int $expiry The expiration time in seconds (default: 3600).
 * @return string The encoded JWT token.
 */
function generateJWT($payload, $secret, $expiry = 3600) {
    // Set the expiration time
    $payload['exp'] = time() + $expiry;

    // Encode the header
    $header = json_encode(['alg' => 'HS256', 'typ' => 'JWT']);
    $header = base64UrlEncode($header);

    // Encode the payload
    $payload = json_encode($payload);
    $payload = base64UrlEncode($payload);

    // Create signature
    $signature = hash_hmac('sha256', $header . "." . $payload, $secret, true);
    $signature = base64UrlEncode($signature);

    // Return the JWT
    return $header . "." . $payload . "." . $signature;
}

/**
 * Decodes a JWT.
 *
 * @param string $jwt The JWT to decode.
 * @param string $secret The secret key to verify the token.
 * @return array|null The decoded payload if valid, or null if invalid.
 */
function validateJWT($jwt, $secret) {
    // Split the JWT into its parts
    $parts = explode('.', $jwt);
    if (count($parts) !== 3) {
        return null; // Invalid token
    }

    list($header, $payload, $signature) = $parts;

    // Recreate the signature to verify
    $expectedSignature = hash_hmac('sha256', $header . "." . $payload, $secret, true);
    $expectedSignature = base64UrlEncode($expectedSignature);

    if ($signature !== $expectedSignature) {
        return null; // Invalid signature
    }

    // Decode the payload
    $payload = json_decode(base64UrlDecode($payload), true);
    if (isset($payload['exp']) && time() > $payload['exp']) {
        return null; // Token expired
    }

    return $payload; // Return the payload if valid
}

/**
 * Encodes data to Base64 URL format.
 *
 * @param string $data The data to encode.
 * @return string The Base64 URL encoded string.
 */
function base64UrlEncode($data) {
    return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
}

/**
 * Decodes Base64 URL format data.
 *
 * @param string $data The data to decode.
 * @return string The decoded string.
 */
function base64UrlDecode($data) {
    $data .= str_repeat('=', (4 - strlen($data) % 4) % 4); // Add padding
    return base64_decode(strtr($data, '-_', '+/'));
}