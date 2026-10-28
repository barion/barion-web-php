<?php

/**
 * Copyright 2026 Barion Payment Inc. All Rights Reserved.
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

use Barion\Helpers\HmacSignature;
use PHPUnit\Framework\TestCase;

/**
 * Tests for HmacSignature. The expected values are the ones Barion's own tests use, so a pass here means
 * the library signs the same text the same way as the Barion API checks it.
 */
class HmacSignatureTest extends TestCase
{
    private const KEY = 'dGVzdF9zZWNyZXRfa2V5';
    private const NONCE = '8c1d6a2e-3f4b-4c55-9a7e-0d2b6f1e9c11';

    // -----------------------------------------------------------------------
    // Signature
    // -----------------------------------------------------------------------

    public function testComputeMatchesBarionVector(): void
    {
        $this->assertSame(
            'Mg3DJsfSE4Hlwhc6DHHuxaDBXBOQUWhP1JkQiDak9ApHXPBiTYMLX0cKcRQd0FrJb+w50Ry2RhBDFDFUWpTIiQ==',
            HmacSignature::Compute(self::KEY, 'test_payload')
        );
    }

    public function testIsValidAcceptsOwnSignature(): void
    {
        $signature = HmacSignature::Compute(self::KEY, 'test_payload');
        $this->assertTrue(HmacSignature::IsValid(self::KEY, 'test_payload', $signature));
    }

    public function testIsValidRejectsChangedPayload(): void
    {
        $signature = HmacSignature::Compute(self::KEY, 'test_payload');
        $this->assertFalse(HmacSignature::IsValid(self::KEY, 'test_payload2', $signature));
    }

    public function testIsValidRejectsInvalidBase64(): void
    {
        $this->assertFalse(HmacSignature::IsValid(self::KEY, 'test_payload', 'not base64!'));
    }

    public function testIsValidRejectsEmptySignature(): void
    {
        $this->assertFalse(HmacSignature::IsValid(self::KEY, 'test_payload', ''));
    }

    public function testIsValidKey(): void
    {
        $this->assertTrue(HmacSignature::IsValidKey(self::KEY));
        $this->assertFalse(HmacSignature::IsValidKey(''));
        $this->assertFalse(HmacSignature::IsValidKey('not base64!'));
    }

    // -----------------------------------------------------------------------
    // Signed text
    // -----------------------------------------------------------------------

    public function testRequestPayloadForPost(): void
    {
        $this->assertSame(
            "1760518800\n" . self::NONCE . "\nPOST\n/v2/payment/refund\n{\"PaymentId\":\"abc\"}",
            HmacSignature::RequestPayload('1760518800', self::NONCE, 'post', '/v2/Payment/Refund', '{"PaymentId":"abc"}')
        );
    }

    public function testRequestPayloadForGetEndsWithEmptyBody(): void
    {
        $this->assertSame(
            "1760518800\n" . self::NONCE . "\nGET\n/v2/payment/getpaymentstate?poskey=x&paymentid=y\n",
            HmacSignature::RequestPayload('1760518800', self::NONCE, 'GET', '/v2/Payment/GetPaymentState?POSKey=x&PaymentId=y', '')
        );
    }

    public function testRequestPayloadLowersPercentEncoding(): void
    {
        $this->assertSame(
            "1760518800\n" . self::NONCE . "\nPOST\n/v2/payment/refund?paymentid=abc%c3%a9\n{}",
            HmacSignature::RequestPayload('1760518800', self::NONCE, 'POST', '/V2/PAYMENT/REFUND?PAYMENTID=abc%C3%a9', '{}')
        );
    }

    public function testResponsePayload(): void
    {
        $this->assertSame(
            "1760518801\n" . self::NONCE . "\n200\n{\"Status\":\"Succeeded\"}",
            HmacSignature::ResponsePayload('1760518801', self::NONCE, 200, '{"Status":"Succeeded"}')
        );
    }

    // -----------------------------------------------------------------------
    // Test vectors of the Barion documentation (Barion_Shop_Authentication#Message_signature_(HMAC))
    // -----------------------------------------------------------------------

    private const DOCS_KEY = 'AAECAwQFBgcICQoLDA0ODxAREhMUFRYXGBkaGxwdHh8gISIjJCUmJygpKissLS4vMDEyMzQ1Njc4OTo7PD0+Pw==';

    public function testDocsVectorRequestWithBody(): void
    {
        $payload = HmacSignature::RequestPayload('1760968800', '3f2b6c1e-8d4a-4b9e-9a51-0c7d2e6f1a84', 'POST',
            '/v2/Payment/Start', '{"PaymentRequestId":"order-1001","Total":10.50}');

        $this->assertSame(
            'AMm7BeXNw0JAs8pxAhemnOQzhlWDz+o/nB85yKPim0DYSCdgpswBQFyQlhFrDwb/MrPFWlcwgDGa+DFGsNJvuQ==',
            HmacSignature::Compute(self::DOCS_KEY, $payload)
        );
    }

    public function testDocsVectorRequestWithoutBody(): void
    {
        $payload = HmacSignature::RequestPayload('1760968800', '9d6a1f0e-2b7c-4c35-8e19-5a4b3c2d1e0f', 'GET',
            '/v4/Payment/GetPaymentState?PaymentId=3F2B6C1E-8D4A-4B9E-9A51-0C7D2E6F1A84', '');

        $this->assertSame(
            'z8YFYV7YKs4OY4eVfd0BSc1LJMkbmBQRFuNXFO+KiAujB7eR1oHwicRTepgVSDqzHoHow/SDoKTdX2IpNMvB8A==',
            HmacSignature::Compute(self::DOCS_KEY, $payload)
        );
    }

    public function testDocsVectorResponse(): void
    {
        $payload = HmacSignature::ResponsePayload('1760968801', '3f2b6c1e-8d4a-4b9e-9a51-0c7d2e6f1a84', 200,
            '{"PaymentId":"3f2b6c1e8d4a4b9e9a510c7d2e6f1a84","Status":"Prepared"}');

        $this->assertTrue(HmacSignature::IsValid(self::DOCS_KEY, $payload,
            '7C8tktY2Bs91dyw7L/fLKwDQCyriGiVOEvbrurn79sLkT2Q93KDVw7c/LEz4VWIqhZOpzLQbs7a6UCo5BZALyQ=='));
    }

    public function testDocsVectorCallback(): void
    {
        $payload = HmacSignature::RequestPayload('1760968802', '7b1e4d2a-6c3f-4a8b-9d50-1e2f3a4b5c6d', 'POST',
            '/hu/DemoClub/Callback?paymentId=4d924e8da6cbf11199f4001dd8b71d1a', 'PaymentId=4d924e8da6cbf11199f4001dd8b71d1a');

        $this->assertTrue(HmacSignature::IsValid(self::DOCS_KEY, $payload,
            'nwEdCk0K1LeDxLqF+4QnUgtmvl0BQPGqLUl+YawMMoJhcNXnJM9xT8JlmsNyWPwWktbJo2oy+yzx6cUMqUrRiA=='));
    }

    // -----------------------------------------------------------------------
    // Nonce
    // -----------------------------------------------------------------------

    public function testNewNonceIsGuid(): void
    {
        $this->assertMatchesRegularExpression(
            '/^[0-9a-f]{8}-[0-9a-f]{4}-4[0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}$/',
            HmacSignature::NewNonce()
        );
    }

    public function testNewNonceIsUnique(): void
    {
        $this->assertNotSame(HmacSignature::NewNonce(), HmacSignature::NewNonce());
    }
}
