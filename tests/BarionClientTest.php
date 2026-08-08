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
use Barion\Models\Payment\{
    PreparePaymentRequestModel,
    FinishReservationRequestModel,
    CaptureRequestModel,
    CancelAuthorizationRequestModel,
    Complete3DSPaymentRequestModel
};
use Barion\Models\Refund\RefundRequestModel;
use PHPUnit\Framework\TestCase;
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
