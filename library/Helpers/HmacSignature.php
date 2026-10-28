<?php

/**
 * Copyright 2026 Barion Payment Inc. All Rights Reserved.
 * <p/>
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 * <p/>
 * http://www.apache.org/licenses/LICENSE-2.0
 * <p/>
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

namespace Barion\Helpers;

/**
 * HMAC-SHA512 signing of the requests sent to, and the responses received from the Barion API.
 *
 * The signed text must match the one Barion builds byte for byte, so any change here breaks every signing shop.
 */
class HmacSignature
{
    public const HEADER_TIMESTAMP = "x-timestamp";
    public const HEADER_NONCE = "x-nonce";
    public const HEADER_SIGNATURE = "x-signature-sha512";

    private const SEPARATOR = "\n";

    /**
     * Builds the text signed for a request.
     * The path and query are signed in lower case, because proxies on the way may change their case.
     *
     * @param string $timestamp Unix time in seconds
     * @param string $nonce A new GUID for every request
     * @param string $method The HTTP method
     * @param string $pathAndQuery The path and query of the URL, without scheme and host
     * @param string $body The raw request body, empty for GET requests
     * @return string
     */
    public static function RequestPayload(string $timestamp, string $nonce, string $method, string $pathAndQuery, string $body): string
    {
        return join(self::SEPARATOR, [$timestamp, $nonce, strtoupper($method), strtolower($pathAndQuery), $body]);
    }

    /**
     * Builds the text signed for a response. The nonce is the one sent with the request.
     *
     * @param string $timestamp Unix time in seconds, as received in the response header
     * @param string $nonce The nonce of the request
     * @param int $statusCode The HTTP status code of the response
     * @param string $body The raw response body
     * @return string
     */
    public static function ResponsePayload(string $timestamp, string $nonce, int $statusCode, string $body): string
    {
        return join(self::SEPARATOR, [$timestamp, $nonce, (string)$statusCode, $body]);
    }

    /**
     * Computes the base64 encoded HMAC-SHA512 signature of the payload.
     *
     * @param string $base64Key The HMAC key of the shop, as shown in Barion (base64 encoded)
     * @param string $payload The text to sign
     * @return string
     */
    public static function Compute(string $base64Key, string $payload): string
    {
        return base64_encode(hash_hmac("sha512", $payload, (string)base64_decode($base64Key, true), true));
    }

    /**
     * Checks a base64 encoded HMAC-SHA512 signature in constant time.
     *
     * @param string $base64Key The HMAC key of the shop (base64 encoded)
     * @param string $payload The signed text
     * @param string $signature The received signature (base64 encoded)
     * @return bool
     */
    public static function IsValid(string $base64Key, string $payload, string $signature): bool
    {
        $received = base64_decode($signature, true);
        if ($received === false || $received === "") {
            return false;
        }

        return hash_equals(hash_hmac("sha512", $payload, (string)base64_decode($base64Key, true), true), $received);
    }

    /**
     * Checks that the key is valid base64 and not empty.
     *
     * @param string $base64Key
     * @return bool
     */
    public static function IsValidKey(string $base64Key): bool
    {
        $key = base64_decode($base64Key, true);
        return $key !== false && $key !== "";
    }

    /**
     * Generates a random (version 4) GUID to be used as the nonce of a request.
     *
     * @return string
     */
    public static function NewNonce(): string
    {
        $bytes = random_bytes(16);
        $bytes[6] = chr((ord($bytes[6]) & 0x0f) | 0x40);
        $bytes[8] = chr((ord($bytes[8]) & 0x3f) | 0x80);
        return vsprintf("%s%s-%s-%s-%s-%s%s%s", str_split(bin2hex($bytes), 4));
    }
}
