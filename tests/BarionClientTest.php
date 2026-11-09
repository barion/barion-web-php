<?php

/**
 * Copyright 2024 Barion Payment Inc. All Rights Reserved.
 * 
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 * 
 * http://www.apache.org/licenses/LICENSE-2.0
 * 
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

namespace Barion\Tests;

use Barion\BarionClient;
use Barion\Enumerations\{
    BarionEnvironment,
    QRCodeSize
};
use Barion\Exceptions\BarionException;
use Barion\Helpers\HmacSignature;
use Barion\Models\Payment\{
    PreparePaymentRequestModel,
    FinishReservationRequestModel,
    CaptureRequestModel,
    CancelAuthorizationRequestModel,
    Complete3DSPaymentRequestModel
};
use Barion\Models\Refund\RefundRequestModel;
use PHPUnit\Framework\TestCase;
use ReflectionMethod;
use ReflectionProperty;

/**
 * Tests for BarionClient
 *
 */
class BarionClientTest extends TestCase
{
    // -----------------------------------------------------------------------
    // Helpers
    // -----------------------------------------------------------------------

    /**
     * Build a client pointed at the test environment.
     * Overriding $version lets version-validation tests pass wrong values easily.
     */
    private function makeClient(int $version = 2, BarionEnvironment $env = BarionEnvironment::Test): BarionClient
    {
        return new BarionClient('test-pos-key-000000000000000000000000', $version, $env);
    }

    /**
     * Read a private/protected property via Reflection so tests do not require
     * exposing internal state through the public API.
     */
    private function getPrivate(object $obj, string $property): mixed
    {
        $ref = new ReflectionProperty($obj, $property);
        return $ref->getValue($obj);
    }

    // -----------------------------------------------------------------------
    // Constructor and environment URL
    // -----------------------------------------------------------------------

    public function testConstructorSetsTestApiUrl(): void
    {
        $client = $this->makeClient(2, BarionEnvironment::Test);
        $this->assertSame(
            BarionClient::BARION_API_URL_TEST,
            $this->getPrivate($client, 'BARION_API_URL')
        );
    }

    public function testConstructorSetsTestWebUrl(): void
    {
        $client = $this->makeClient(2, BarionEnvironment::Test);
        $this->assertSame(
            BarionClient::BARION_WEB_URL_TEST,
            $this->getPrivate($client, 'BARION_WEB_URL')
        );
    }

    public function testConstructorSetsProdApiUrl(): void
    {
        $client = $this->makeClient(2, BarionEnvironment::Prod);
        $this->assertSame(
            BarionClient::BARION_API_URL_PROD,
            $this->getPrivate($client, 'BARION_API_URL')
        );
    }

    public function testConstructorSetsProdWebUrl(): void
    {
        $client = $this->makeClient(2, BarionEnvironment::Prod);
        $this->assertSame(
            BarionClient::BARION_WEB_URL_PROD,
            $this->getPrivate($client, 'BARION_WEB_URL')
        );
    }

    // -----------------------------------------------------------------------
    // POSKey and API version
    // -----------------------------------------------------------------------

    public function testConstructorStoresPosKey(): void
    {
        $posKey = 'unique-pos-key-abc123';
        $client = new BarionClient($posKey, 2, BarionEnvironment::Test);
        $this->assertSame($posKey, $this->getPrivate($client, 'POSKey'));
    }

    public function testConstructorStoresApiVersion(): void
    {
        $client = $this->makeClient(2);
        $this->assertSame(2, $this->getPrivate($client, 'APIVersion'));
    }

    public function testConstructorStoresAlternativeApiVersion(): void
    {
        $client = $this->makeClient(4);
        $this->assertSame(4, $this->getPrivate($client, 'APIVersion'));
    }

    // -----------------------------------------------------------------------
    // Setting version
    // -----------------------------------------------------------------------

    public function testSetVersionUpdatesStoredVersion(): void
    {
        $client = $this->makeClient(2);
        $client->SetVersion(4);
        $this->assertSame(4, $this->getPrivate($client, 'APIVersion'));
    }

    public function testSetVersionCanSwitchBackToTwo(): void
    {
        $client = $this->makeClient(4);
        $client->SetVersion(2);
        $this->assertSame(2, $this->getPrivate($client, 'APIVersion'));
    }

    // -----------------------------------------------------------------------
    // HMAC signing
    // -----------------------------------------------------------------------

    private const HMAC_KEY = 'dGVzdF9zZWNyZXRfa2V5';
    private const NEXT_HMAC_KEY = 'bmV4dF9zZWNyZXRfa2V5';

    /**
     * Call a private method via Reflection, passing the arguments by reference where the method expects it.
     *
     * @param array<int, mixed> $args
     */
    private function callPrivate(object $obj, string $method, array &$args): mixed
    {
        $ref = new ReflectionMethod($obj, $method);
        return $ref->invokeArgs($obj, $args);
    }

    public function testHmacKeyIsNotSetByDefault(): void
    {
        $this->assertNull($this->getPrivate($this->makeClient(), 'HmacKey'));
    }

    public function testSetHmacKeyStoresKey(): void
    {
        $client = $this->makeClient();
        $client->SetHmacKey(self::HMAC_KEY);
        $this->assertSame(self::HMAC_KEY, $this->getPrivate($client, 'HmacKey'));
    }

    public function testSetHmacKeyThrowsOnInvalidKey(): void
    {
        $this->expectException(BarionException::class);
        $this->makeClient()->SetHmacKey('not base64!');
    }

    public function testRequestIsNotSignedWithoutKey(): void
    {
        $headers = ['x-pos-key: key'];
        $args = [&$headers, 'POST', 'https://api.test.barion.com/v2/Payment/Start', '{}'];

        $this->assertNull($this->callPrivate($this->makeClient(), 'AddSignatureHeaders', $args));
        $this->assertSame(['x-pos-key: key'], $headers);
    }

    public function testPostRequestIsSigned(): void
    {
        $client = $this->makeClient();
        $client->SetHmacKey(self::HMAC_KEY);
        $headers = [];
        $args = [&$headers, 'POST', 'https://api.test.barion.com/v2/Payment/Start', '{"PaymentId":"abc"}'];

        $nonce = $this->callPrivate($client, 'AddSignatureHeaders', $args);

        $this->assertCount(3, $headers);
        $this->assertSame('x-nonce: ' . $nonce, $headers[1]);
        $timestamp = substr($headers[0], strlen('x-timestamp: '));
        $this->assertEqualsWithDelta(time(), (int)$timestamp, 5);
        $payload = HmacSignature::RequestPayload($timestamp, $nonce, 'POST', '/v2/Payment/Start', '{"PaymentId":"abc"}');
        $this->assertSame('x-signature-sha512: ' . HmacSignature::Compute(self::HMAC_KEY, $payload), $headers[2]);
    }

    public function testGetRequestSignsPathAndQueryWithoutHost(): void
    {
        $client = $this->makeClient();
        $client->SetHmacKey(self::HMAC_KEY);
        $headers = [];
        $args = [&$headers, 'GET', 'https://api.test.barion.com/v2/Payment/GetPaymentState?POSKey=x&PaymentId=y', ''];

        $nonce = $this->callPrivate($client, 'AddSignatureHeaders', $args);

        $timestamp = substr($headers[0], strlen('x-timestamp: '));
        $payload = "$timestamp\n$nonce\nGET\n/v2/payment/getpaymentstate?poskey=x&paymentid=y\n";
        $this->assertSame('x-signature-sha512: ' . HmacSignature::Compute(self::HMAC_KEY, $payload), $headers[2]);
    }

    /**
     * Check a 200 response to a request with the nonce 'nonce' and the body '{}', signed as the parameters say.
     * Returns the error code the response is discarded for, or null if it is accepted.
     */
    private function checkResponse(BarionClient $client, string $signingKey, int $timestamp, string $signedNonce = 'nonce', string $signedBody = '{}'): ?string
    {
        $signature = HmacSignature::Compute($signingKey, HmacSignature::ResponsePayload((string)$timestamp, $signedNonce, 200, $signedBody));
        $args = [['x-timestamp' => (string)$timestamp, 'x-signature-sha512' => $signature], 'nonce', 200, '{}'];

        return $this->callPrivate($client, 'CheckResponseSignature', $args)?->ErrorCode;
    }

    public function testUnsignedResponseIsAccepted(): void
    {
        $client = $this->makeClient();
        $client->SetHmacKey(self::HMAC_KEY);
        $args = [[], 'nonce', 401, '{}'];

        $this->assertNull($this->callPrivate($client, 'CheckResponseSignature', $args));
    }

    public function testSignedResponseIsAccepted(): void
    {
        $client = $this->makeClient();
        $client->SetHmacKey(self::HMAC_KEY);

        $this->assertNull($this->checkResponse($client, self::HMAC_KEY, time()));
    }

    public function testResponseWithChangedBodyIsRejected(): void
    {
        $client = $this->makeClient();
        $client->SetHmacKey(self::HMAC_KEY);

        $this->assertSame('InvalidResponseSignature', $this->checkResponse($client, self::HMAC_KEY, time(), signedBody: '{"Status":"Succeeded"}'));
    }

    public function testResponseWithinTimestampToleranceIsAccepted(): void
    {
        $client = $this->makeClient();
        $client->SetHmacKey(self::HMAC_KEY);

        $this->assertNull($this->checkResponse($client, self::HMAC_KEY, time() - 290));
        $this->assertNull($this->checkResponse($client, self::HMAC_KEY, time() + 290));
    }

    public function testResponseWithOldTimestampIsRejected(): void
    {
        $client = $this->makeClient();
        $client->SetHmacKey(self::HMAC_KEY);

        $this->assertSame('InvalidResponseTimestamp', $this->checkResponse($client, self::HMAC_KEY, time() - 310));
    }

    public function testResponseWithFutureTimestampIsRejected(): void
    {
        $client = $this->makeClient();
        $client->SetHmacKey(self::HMAC_KEY);

        $this->assertSame('InvalidResponseTimestamp', $this->checkResponse($client, self::HMAC_KEY, time() + 310));
    }

    public function testResponseWithoutTimestampIsRejected(): void
    {
        $client = $this->makeClient();
        $client->SetHmacKey(self::HMAC_KEY);
        $signature = HmacSignature::Compute(self::HMAC_KEY, HmacSignature::ResponsePayload('', 'nonce', 200, '{}'));
        $args = [['x-signature-sha512' => $signature], 'nonce', 200, '{}'];

        $this->assertSame('InvalidResponseTimestamp', $this->callPrivate($client, 'CheckResponseSignature', $args)?->ErrorCode);
    }

    public function testSetHmacKeyStoresNextKey(): void
    {
        $client = $this->makeClient();
        $client->SetHmacKey(self::HMAC_KEY, self::NEXT_HMAC_KEY);
        $this->assertSame(self::HMAC_KEY, $this->getPrivate($client, 'HmacKey'));
        $this->assertSame(self::NEXT_HMAC_KEY, $this->getPrivate($client, 'NextHmacKey'));
    }

    public function testSetHmacKeyClearsNextKey(): void
    {
        $client = $this->makeClient();
        $client->SetHmacKey(self::HMAC_KEY, self::NEXT_HMAC_KEY);
        $client->SetHmacKey(self::NEXT_HMAC_KEY);
        $this->assertNull($this->getPrivate($client, 'NextHmacKey'));
    }

    public function testSetHmacKeyThrowsOnInvalidNextKey(): void
    {
        $this->expectException(BarionException::class);
        $this->makeClient()->SetHmacKey(self::HMAC_KEY, 'not base64!');
    }

    public function testRequestIsSignedWithCurrentKeyDuringRotation(): void
    {
        $client = $this->makeClient();
        $client->SetHmacKey(self::HMAC_KEY, self::NEXT_HMAC_KEY);
        $headers = [];
        $args = [&$headers, 'POST', 'https://api.test.barion.com/v2/Payment/Start', '{}'];

        $nonce = $this->callPrivate($client, 'AddSignatureHeaders', $args);

        $timestamp = substr($headers[0], strlen('x-timestamp: '));
        $payload = HmacSignature::RequestPayload($timestamp, $nonce, 'POST', '/v2/Payment/Start', '{}');
        $this->assertSame('x-signature-sha512: ' . HmacSignature::Compute(self::HMAC_KEY, $payload), $headers[2]);
    }

    public function testResponseSignedWithNextKeyIsAcceptedDuringRotation(): void
    {
        $client = $this->makeClient();
        $client->SetHmacKey(self::HMAC_KEY, self::NEXT_HMAC_KEY);

        $this->assertNull($this->checkResponse($client, self::NEXT_HMAC_KEY, time()));
    }

    public function testResponseSignedWithNextKeyIsRejectedWithoutRotation(): void
    {
        $client = $this->makeClient();
        $client->SetHmacKey(self::HMAC_KEY);

        $this->assertSame('InvalidResponseSignature', $this->checkResponse($client, self::NEXT_HMAC_KEY, time()));
    }

    // -----------------------------------------------------------------------
    // Callback (IPN) verification
    // -----------------------------------------------------------------------

    private const CALLBACK_URI = '/hu/DemoClub/Callback?paymentId=4d924e8da6cbf11199f4001dd8b71d1a';
    private const CALLBACK_BODY = 'PaymentId=4d924e8da6cbf11199f4001dd8b71d1a';

    /**
     * Build the headers Barion sends with a callback to CALLBACK_URI with CALLBACK_BODY.
     *
     * @return array<string, string>
     */
    private function callbackHeaders(string $signingKey, int $timestamp): array
    {
        $nonce = '7b1e4d2a-6c3f-4a8b-9d50-1e2f3a4b5c6d';
        $payload = HmacSignature::RequestPayload((string)$timestamp, $nonce, 'POST', self::CALLBACK_URI, self::CALLBACK_BODY);

        return [
            'x-timestamp' => (string)$timestamp,
            'x-nonce' => $nonce,
            'x-signature-sha512' => HmacSignature::Compute($signingKey, $payload)
        ];
    }

    private function makeSigningClient(?string $nextKey = null): BarionClient
    {
        $client = $this->makeClient();
        $client->SetHmacKey(self::HMAC_KEY, $nextKey);
        return $client;
    }

    public function testCallbackCheckThrowsWithoutKey(): void
    {
        $this->expectException(BarionException::class);
        $this->makeClient()->IsCallbackValid(self::CALLBACK_URI, self::CALLBACK_BODY, []);
    }

    public function testSignedCallbackIsValid(): void
    {
        $headers = $this->callbackHeaders(self::HMAC_KEY, time());
        $this->assertTrue($this->makeSigningClient()->IsCallbackValid(self::CALLBACK_URI, self::CALLBACK_BODY, $headers));
    }

    public function testUnsignedCallbackIsInvalid(): void
    {
        $this->assertFalse($this->makeSigningClient()->IsCallbackValid(self::CALLBACK_URI, self::CALLBACK_BODY, []));
    }

    public function testCallbackWithoutNonceIsInvalid(): void
    {
        $headers = $this->callbackHeaders(self::HMAC_KEY, time());
        unset($headers['x-nonce']);
        $this->assertFalse($this->makeSigningClient()->IsCallbackValid(self::CALLBACK_URI, self::CALLBACK_BODY, $headers));
    }

    public function testCallbackWithChangedBodyIsInvalid(): void
    {
        $headers = $this->callbackHeaders(self::HMAC_KEY, time());
        $this->assertFalse($this->makeSigningClient()->IsCallbackValid(self::CALLBACK_URI, 'PaymentId=00000000000000000000000000000000', $headers));
    }

    public function testCallbackToAnotherUrlIsInvalid(): void
    {
        $headers = $this->callbackHeaders(self::HMAC_KEY, time());
        $this->assertFalse($this->makeSigningClient()->IsCallbackValid('/other/callback?paymentId=4d924e8da6cbf11199f4001dd8b71d1a', self::CALLBACK_BODY, $headers));
    }

    public function testCallbackWithOldTimestampIsInvalid(): void
    {
        $headers = $this->callbackHeaders(self::HMAC_KEY, time() - 310);
        $this->assertFalse($this->makeSigningClient()->IsCallbackValid(self::CALLBACK_URI, self::CALLBACK_BODY, $headers));
    }

    public function testCallbackSignedWithNextKeyIsValidDuringRotation(): void
    {
        $headers = $this->callbackHeaders(self::NEXT_HMAC_KEY, time());
        $this->assertTrue($this->makeSigningClient(self::NEXT_HMAC_KEY)->IsCallbackValid(self::CALLBACK_URI, self::CALLBACK_BODY, $headers));
    }

    public function testCallbackSignedWithNextKeyIsInvalidWithoutRotation(): void
    {
        $headers = $this->callbackHeaders(self::NEXT_HMAC_KEY, time());
        $this->assertFalse($this->makeSigningClient()->IsCallbackValid(self::CALLBACK_URI, self::CALLBACK_BODY, $headers));
    }

    public function testReplayedCallbackIsInvalidWithNonceStore(): void
    {
        $client = $this->makeSigningClient();
        $headers = $this->callbackHeaders(self::HMAC_KEY, time());
        $usedNonces = [];
        $isNonceNew = function (string $nonce) use (&$usedNonces): bool {
            if (isset($usedNonces[$nonce])) {
                return false;
            }
            $usedNonces[$nonce] = true;
            return true;
        };

        $this->assertTrue($client->IsCallbackValid(self::CALLBACK_URI, self::CALLBACK_BODY, $headers, $isNonceNew));
        $this->assertFalse($client->IsCallbackValid(self::CALLBACK_URI, self::CALLBACK_BODY, $headers, $isNonceNew));
        $this->assertSame([$headers['x-nonce']], array_keys($usedNonces));
    }

    public function testNonceStoreIsNotCalledForInvalidCallback(): void
    {
        $headers = $this->callbackHeaders(self::HMAC_KEY, time() - 310);
        $called = false;
        $isNonceNew = function (string $nonce) use (&$called): bool {
            $called = true;
            return true;
        };

        $this->assertFalse($this->makeSigningClient()->IsCallbackValid(self::CALLBACK_URI, self::CALLBACK_BODY, $headers, $isNonceNew));
        $this->assertFalse($called);
    }

    public function testCallbackCheckReadsCurrentRequest(): void
    {
        $headers = $this->callbackHeaders(self::HMAC_KEY, time());
        $_SERVER['REQUEST_URI'] = self::CALLBACK_URI;
        $_SERVER['HTTP_X_TIMESTAMP'] = $headers['x-timestamp'];
        $_SERVER['HTTP_X_NONCE'] = $headers['x-nonce'];
        $_SERVER['HTTP_X_SIGNATURE_SHA512'] = $headers['x-signature-sha512'];

        try {
            // php://input is empty on the command line, so only the body is passed
            $this->assertTrue($this->makeSigningClient()->IsCallbackValid(body: self::CALLBACK_BODY));
        } finally {
            unset($_SERVER['REQUEST_URI'], $_SERVER['HTTP_X_TIMESTAMP'], $_SERVER['HTTP_X_NONCE'], $_SERVER['HTTP_X_SIGNATURE_SHA512']);
        }
    }

    public function testResponseToAnotherRequestIsRejected(): void
    {
        $client = $this->makeClient();
        $client->SetHmacKey(self::HMAC_KEY);

        $this->assertSame('InvalidResponseSignature', $this->checkResponse($client, self::HMAC_KEY, time(), signedNonce: 'other-nonce'));
    }

    // -----------------------------------------------------------------------
    // PreparePayment (v2)
    // -----------------------------------------------------------------------

    public function testPreparePaymentThrowsOnVersionMismatch(): void
    {
        $this->expectException(BarionException::class);
        $this->expectExceptionMessageMatches('/Incorrect API version for Payment Prepare/');

        $this->makeClient(version: 1)->PreparePayment(new PreparePaymentRequestModel('req-001'));
    }

    public function testPreparePaymentThrowsWhenVersionIsFour(): void
    {
        $this->expectException(BarionException::class);
        $this->expectExceptionMessageMatches('/Incorrect API version for Payment Prepare/');

        $this->makeClient(version: 4)->PreparePayment(new PreparePaymentRequestModel('req-002'));
    }

    // -----------------------------------------------------------------------
    // FinishReservation (v2)
    // -----------------------------------------------------------------------

    public function testFinishReservationThrowsOnVersionMismatch(): void
    {
        $this->expectException(BarionException::class);
        $this->expectExceptionMessageMatches('/Incorrect API version for Finish Reservation/');

        $this->makeClient(version: 1)->FinishReservation(new FinishReservationRequestModel('payment-id-001'));
    }

    // -----------------------------------------------------------------------
    // Capture (v2)
    // -----------------------------------------------------------------------

    public function testCaptureThrowsOnVersionMismatch(): void
    {
        $this->expectException(BarionException::class);
        $this->expectExceptionMessageMatches('/Incorrect API version for Capture/');

        $this->makeClient(version: 1)->Capture(new CaptureRequestModel('payment-id-002'));
    }

    // -----------------------------------------------------------------------
    // CancelAuthorization (v2)
    // -----------------------------------------------------------------------

    public function testCancelAuthorizationThrowsOnVersionMismatch(): void
    {
        $this->expectException(BarionException::class);
        $this->expectExceptionMessageMatches('/Incorrect API version for Cancel Authorization/');

        $this->makeClient(version: 1)->CancelAuthorization(new CancelAuthorizationRequestModel('payment-id-003'));
    }

    // -----------------------------------------------------------------------
    // Complete3DSPayment (v2)
    // -----------------------------------------------------------------------

    public function testComplete3DSPaymentThrowsOnVersionMismatch(): void
    {
        $this->expectException(BarionException::class);
        $this->expectExceptionMessageMatches('/Incorrect API version for 3DS Complete/');

        $this->makeClient(version: 1)->Complete3DSPayment(new Complete3DSPaymentRequestModel('payment-id-004'));
    }

    // -----------------------------------------------------------------------
    // RefundPayment (v2)
    // -----------------------------------------------------------------------

    public function testRefundPaymentThrowsOnVersionMismatch(): void
    {
        $this->expectException(BarionException::class);
        $this->expectExceptionMessageMatches('/Incorrect API version for Refund/');

        $this->makeClient(version: 1)->RefundPayment(new RefundRequestModel('payment-id-005'));
    }

    // -----------------------------------------------------------------------
    // GetPaymentState (v2)
    // -----------------------------------------------------------------------

    public function testGetPaymentStateThrowsOnVersionMismatch(): void
    {
        $this->expectException(BarionException::class);
        $this->expectExceptionMessageMatches('/Incorrect API version for GetPaymentState/');

        $this->makeClient(version: 1)->GetPaymentState('payment-id-006');
    }

    // -----------------------------------------------------------------------
    // PaymentState (v4)
    // -----------------------------------------------------------------------

    public function testPaymentStateIncorrectVersion(): void
    {
        $this->expectException(BarionException::class);
        $this->expectExceptionMessageMatches('/Incorrect API version for PaymentState/');

        $this->makeClient(version: 2)->PaymentState('payment-id-007');
    }

    // -----------------------------------------------------------------------
    // GetPaymentQRImage (v1)
    // -----------------------------------------------------------------------

    public function testGetPaymentQRImageThrowsWhenVersionIsTwo(): void
    {
        $this->expectException(BarionException::class);
        $this->expectExceptionMessageMatches('/Incorrect API version for QR Code/');

        $this->makeClient(version: 2)->GetPaymentQRImage('user@example.com', 'examplepassword', 'payment-id-009');
    }
}
