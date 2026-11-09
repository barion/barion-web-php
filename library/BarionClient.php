<?php

/**
 * Copyright 2024 Barion Payment Inc. All Rights Reserved.
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

/*
* BARION WEB PHP
* PHP library for implementing REST API calls towards the Barion payment system.
*
* https://docs.barion.com
* https://www.barion.com
*/

namespace Barion;

/*
* Autoloading necessary files. Uses own autoloader if Composer autoload is not present.
*/
if (file_exists(__DIR__ . '/../vendor/autoload.php')) {
    require_once __DIR__ . '/../vendor/autoload.php';
} else {
    require_once 'autoload.php';
}

/* -------- IMPORTED CLASSES -------- */

use Barion\Enumerations\{
    BarionEnvironment,
    QRCodeSize
};
use Barion\Exceptions\BarionException;
use Barion\Helpers\HmacSignature;
use Barion\Models\{
    BaseRequestModel,
    BaseResponseModel,
    HttpResponseModel
};
use Barion\Models\Error\{
    ApiErrorModel
};
use CurlHandle;
use Barion\Models\Payment\{
    PreparePaymentRequestModel,
    PreparePaymentResponseModel,
    FinishReservationRequestModel,
    FinishReservationResponseModel,
    GetPaymentStateRequestModel,
    GetPaymentStateResponseModel,
    CaptureRequestModel,
    CaptureResponseModel,
    CancelAuthorizationRequestModel,
    CancelAuthorizationResponseModel,
    Complete3DSPaymentRequestModel,
    Complete3DSPaymentResponseModel,
    PaymentStateRequestModel,
    PaymentStateResponseModel,
    PaymentQRRequestModel
};
use Barion\Models\Refund\{
    RefundRequestModel,
    RefundResponseModel
};

/* -------- CLASS DEFINITION -------- */

class BarionClient
{
    /* -------- CONSTANTS -------- */

    private const MINIMUM_PHP_VERSION               = "8.2";
    private const HMAC_TIMESTAMP_TOLERANCE_SECONDS  = 300;

    public const BARION_API_URL_PROD               = "https://api.barion.com";
    public const BARION_WEB_URL_PROD               = "https://secure.barion.com/Pay";
    public const BARION_API_URL_TEST               = "https://api.test.barion.com";
    public const BARION_WEB_URL_TEST               = "https://secure.test.barion.com/Pay";

    public const API_ENDPOINT_PREPAREPAYMENT       = "/Payment/Start";
    public const API_ENDPOINT_GETPAYMENTSTATE      = "/Payment/GetPaymentState";
    public const API_ENDPOINT_PAYMENTSTATE         = "/Payment/{paymentId}/PaymentState";
    public const API_ENDPOINT_QRCODE               = "/QR/Generate";
    public const API_ENDPOINT_REFUND               = "/Payment/Refund";
    public const API_ENDPOINT_FINISHRESERVATION    = "/Payment/FinishReservation";
    public const API_ENDPOINT_CAPTURE              = "/Payment/Capture";
    public const API_ENDPOINT_CANCELAUTHORIZATION  = "/Payment/CancelAuthorization";
    public const API_ENDPOINT_3DS_COMPLETE         = "/Payment/Complete";

    private BarionEnvironment $Environment;

    private int $APIVersion;
    private string $POSKey;
    private ?string $HmacKey = null;
    private ?string $NextHmacKey = null;

    private string $BARION_API_URL = "";
    private string $BARION_WEB_URL = "";

    private bool $UseBundledRootCertificates;

    /**
     * Create a new instance of the Barion API client.
     *
     * @param string $poskey The secret POSKey of your shop
     * @param int $version The version of the Barion API
     * @param BarionEnvironment $env The environment to connect to
     * @param bool $useBundledRootCerts Set this to true to use the library-bundled root certificate chain for SSL (only recommended as a last resort, if you are having connection problems)
     * @throws BarionException
     */
    function __construct(string $poskey, int $version = 2, BarionEnvironment $env = BarionEnvironment::Prod, bool $useBundledRootCerts = false)
    {
        // check for minimum PHP version
        if (version_compare(phpversion(), BarionClient::MINIMUM_PHP_VERSION, '<')) {
            throw new BarionException("The Barion PHP library requires at least PHP version ".BarionClient::MINIMUM_PHP_VERSION." to function. Please update your PHP installation.");
        }

        // check for cURL extension
        if (!extension_loaded('curl')) {
            throw new BarionException("The Barion PHP library requires the cURL module to function. Please check your system configuration.");
        }

        $this->POSKey = $poskey;
        $this->APIVersion = $version;
        $this->Environment = $env;

        switch ($env) {

            case BarionEnvironment::Test:
                $this->BARION_API_URL = BarionClient::BARION_API_URL_TEST;
                $this->BARION_WEB_URL = BarionClient::BARION_WEB_URL_TEST;
                break;

            case BarionEnvironment::Prod:
            default:
                $this->BARION_API_URL = BarionClient::BARION_API_URL_PROD;
                $this->BARION_WEB_URL = BarionClient::BARION_WEB_URL_PROD;
                break;
        }

        $this->UseBundledRootCertificates = $useBundledRootCerts;
    }

    /**
     * Sets the API version for the client. Useful if one instance manages different API version calls.
     *
     * @param int $version The version of the Barion API
     * @return void
     */
    public function SetVersion(int $version): void
    {
        $this->APIVersion = $version;
    }

    /**
     * Sets the HMAC key of the shop. Once set, every request is signed with it, and every signed response is checked.
     * Barion ignores the signature until the key is activated, so the key can be set before the activation.
     *
     * When rotating the key, pass the new key as $nextHmacKey before activating it in Barion: requests are still signed
     * with $hmacKey, which Barion accepts for 24 hours after the activation, while the responses Barion signs with
     * the new key are accepted too. Within those 24 hours, switch to SetHmacKey(<the new key>).
     *
     * @param string $hmacKey The HMAC key of the shop, as shown in Barion (base64 encoded)
     * @param ?string $nextHmacKey The key the shop is rotating to, only used to check responses (base64 encoded)
     * @return void
     * @throws BarionException
     */
    public function SetHmacKey(string $hmacKey, ?string $nextHmacKey = null): void
    {
        if (!HmacSignature::IsValidKey($hmacKey) || ($nextHmacKey !== null && !HmacSignature::IsValidKey($nextHmacKey))) {
            throw new BarionException("The HMAC key must be the base64 encoded key shown in Barion.");
        }

        $this->HmacKey = $hmacKey;
        $this->NextHmacKey = $nextHmacKey;
    }

    /* -------- BARION API CALL IMPLEMENTATIONS -------- */


    /**
     * Prepare a new payment
     *
     * @param PreparePaymentRequestModel $model The request model for payment preparation
     * @return PreparePaymentResponseModel Returns the response from the Barion API
     *
     * @throws BarionException
     */
    public function PreparePayment(PreparePaymentRequestModel $model): PreparePaymentResponseModel
    {
        if ($this->APIVersion != 2) {
            throw new BarionException("Incorrect API version for Payment Prepare endpoint! Current: $this->APIVersion. Expected: 2.");
        }

        $model->POSKey = $this->POSKey;
        $url = $this->BARION_API_URL . "/v" . $this->APIVersion . BarionClient::API_ENDPOINT_PREPAREPAYMENT;
        $response = $this->PostToBarion($url, $model);
        $rm = new PreparePaymentResponseModel();
        $json = json_decode((string)$response->Body, true);
        if (is_array($json)) {
            /** @var array<string, mixed> $json */
            $rm->fromJson($json);
            if (!empty($rm->PaymentId)) {
                $rm->PaymentRedirectUrl = $this->BARION_WEB_URL . "?" . http_build_query(array("id" => $rm->PaymentId));
            }
        }
        $rm->HttpStatusCode = $response->StatusCode;
        return $rm;
    }

    /**
     *
     * Finish an existing reservation
     *
     * @param FinishReservationRequestModel $model The request model for the finish process
     * @return FinishReservationResponseModel Returns the response from the Barion API
     *
     * @throws BarionException
     */
    public function FinishReservation(FinishReservationRequestModel $model): FinishReservationResponseModel
    {
        if ($this->APIVersion != 2) {
            throw new BarionException("Incorrect API version for Finish Reservation endpoint! Current: $this->APIVersion. Expected: 2.");
        }

        $model->POSKey = $this->POSKey;
        $url = $this->BARION_API_URL . "/v" . $this->APIVersion . BarionClient::API_ENDPOINT_FINISHRESERVATION;
        $response = $this->PostToBarion($url, $model);
        $rm = new FinishReservationResponseModel();
        $json = json_decode((string)$response->Body, true);
        if (is_array($json)) {
            /** @var array<string, mixed> $json */
            $rm->fromJson($json);
        }
        $rm->HttpStatusCode = $response->StatusCode;
        return $rm;
    }

    /**
     *
     * Capture the previously authorized money in a Delayed Capture payment
     *
     * @param CaptureRequestModel $model The request model for the capture process
     * @return CaptureResponseModel Returns the response from the Barion API
     *
     * @throws BarionException
     */
    public function Capture(CaptureRequestModel $model): CaptureResponseModel
    {
        if ($this->APIVersion != 2) {
            throw new BarionException("Incorrect API version for Capture endpoint! Current: $this->APIVersion. Expected: 2.");
        }

        $model->POSKey = $this->POSKey;
        $url = $this->BARION_API_URL . "/v" . $this->APIVersion . BarionClient::API_ENDPOINT_CAPTURE;
        $response = $this->PostToBarion($url, $model);
        $captureResponse = new CaptureResponseModel();
        $json = json_decode((string)$response->Body, true);
        if (is_array($json)) {
            /** @var array<string, mixed> $json */
            $captureResponse->fromJson($json);
        }
        $captureResponse->HttpStatusCode = $response->StatusCode;
        return $captureResponse;
    }

    /**
     *
     * Cancel a pending authorization on a Delayed Capture payment
     *
     * @param CancelAuthorizationRequestModel $model The request model for cancelling the authorization
     * @return CancelAuthorizationResponseModel Returns the response from the Barion API
     *
     * @throws BarionException
     */
    public function CancelAuthorization(CancelAuthorizationRequestModel $model): CancelAuthorizationResponseModel
    {
        if ($this->APIVersion != 2) {
            throw new BarionException("Incorrect API version for Cancel Authorization endpoint! Current: $this->APIVersion. Expected: 2.");
        }

        $model->POSKey = $this->POSKey;
        $url = $this->BARION_API_URL . "/v" . $this->APIVersion . BarionClient::API_ENDPOINT_CANCELAUTHORIZATION;
        $response = $this->PostToBarion($url, $model);
        $cancelAuthResponse = new CancelAuthorizationResponseModel();
        $json = json_decode((string)$response->Body, true);
        if (is_array($json)) {
            /** @var array<string, mixed> $json */
            $cancelAuthResponse->fromJson($json);
        }
        $cancelAuthResponse->HttpStatusCode = $response->StatusCode;
        return $cancelAuthResponse;
    }

    /**
     * Complete a previously 3DSecure-authenticated payment
     *
     * @param Complete3DSPaymentRequestModel $model The request model for completing the authenticated payment
     * @return Complete3DSPaymentResponseModel Returns the response from the Barion API
     *
     * @throws BarionException
     */
    public function Complete3DSPayment(Complete3DSPaymentRequestModel $model): Complete3DSPaymentResponseModel
    {
        if ($this->APIVersion != 2) {
            throw new BarionException("Incorrect API version for 3DS Complete endpoint! Current: $this->APIVersion. Expected: 2.");
        }

        $model->POSKey = $this->POSKey;
        $url = $this->BARION_API_URL . "/v" . $this->APIVersion . BarionClient::API_ENDPOINT_3DS_COMPLETE;
        $response = $this->PostToBarion($url, $model);
        $rm = new Complete3DSPaymentResponseModel();
        $json = json_decode((string)$response->Body, true);
        if (is_array($json)) {
            /** @var array<string, mixed> $json */
            $rm->fromJson($json);
        }
        $rm->HttpStatusCode = $response->StatusCode;
        return $rm;
    }

    /**
     * Refund a payment partially or totally
     *
     * @param RefundRequestModel $model The request model for the refund process
     * @return RefundResponseModel Returns the response from the Barion API
     *
     * @throws BarionException
     */
    public function RefundPayment(RefundRequestModel $model): RefundResponseModel
    {
        if ($this->APIVersion != 2) {
            throw new BarionException("Incorrect API version for Refund endpoint! Current: $this->APIVersion. Expected: 2.");
        }

        $model->POSKey = $this->POSKey;
        $url = $this->BARION_API_URL . "/v" . $this->APIVersion . BarionClient::API_ENDPOINT_REFUND;
        $response = $this->PostToBarion($url, $model);
        $rm = new RefundResponseModel();
        $json = json_decode((string)$response->Body, true);
        if (is_array($json)) {
            /** @var array<string, mixed> $json */
            $rm->fromJson($json);
        }
        $rm->HttpStatusCode = $response->StatusCode;
        return $rm;
    }

    /**
     * Get detailed information about a given payment
     *
     * @param string $paymentId The ID of the payment
     * @return GetPaymentStateResponseModel Returns the response from the Barion API
     *
     * @throws BarionException
     * @deprecated
     */
    public function GetPaymentState(string $paymentId): GetPaymentStateResponseModel
    {
        if ($this->APIVersion != 2) {
            throw new BarionException("Incorrect API version for GetPaymentState endpoint! Current: $this->APIVersion. Expected: 2.");
        }

        $model = new GetPaymentStateRequestModel($paymentId);
        $model->POSKey = $this->POSKey;
        $url = $this->BARION_API_URL . "/v" . $this->APIVersion . BarionClient::API_ENDPOINT_GETPAYMENTSTATE;
        $response = $this->GetFromBarion($url, $model);
        $ps = new GetPaymentStateResponseModel();
        $json = json_decode((string)$response->Body, true);
        if (is_array($json)) {
            /** @var array<string, mixed> $json */
            $ps->fromJson($json);
        }
        $ps->HttpStatusCode = $response->StatusCode;
        return $ps;
    }

    /**
     * Get detailed information about a given payment
     *
     * @param string $paymentId The ID of the payment
     * @return PaymentStateResponseModel Returns the response from the Barion API
     *
     * @throws BarionException
     */
    public function PaymentState(string $paymentId): PaymentStateResponseModel
    {
        if ($this->APIVersion != 4) {
            throw new BarionException("Incorrect API version for PaymentState endpoint! Current: $this->APIVersion. Expected: 4.");
        }

        $model = new PaymentStateRequestModel($paymentId);
        $model->POSKey = $this->POSKey;
        $url = $this->BARION_API_URL . "/v" . $this->APIVersion . str_ireplace("{paymentId}", $paymentId, BarionClient::API_ENDPOINT_PAYMENTSTATE);
        $response = $this->GetFromBarion($url, $model);
        $ps = new PaymentStateResponseModel();
        $json = json_decode((string)$response->Body, true);
        if (is_array($json)) {
            /** @var array<string, mixed> $json */
            $ps->fromJson($json);
        }
        $ps->HttpStatusCode = $response->StatusCode;
        return $ps;
    }

    /**
     * Get the QR code image for a given payment
     *
     * NOTE: This call is deprecated and is only working with username & password authentication.
     * If no username and/or password was set, this method returns NULL.
     *
     * @param string $username The username of the shop's owner
     * @param string $password The password of the shop's owner
     * @param string $paymentId The ID of the payment
     * @param QRCodeSize $qrCodeSize The desired size of the QR image
     * @return string Returns the response of the QR request
     *
     * @throws BarionException
     *@deprecated
     */
    public function GetPaymentQRImage(string $username, string $password, string $paymentId, QRCodeSize $qrCodeSize = QRCodeSize::Large): string
    {
        if ($this->APIVersion != 1) {
            throw new BarionException("Incorrect API version for QR Code endpoint! Current: $this->APIVersion. Expected: 1.");
        }

        $model = new PaymentQRRequestModel($username, $password, $paymentId);
        $model->POSKey = $this->POSKey;
        $model->Size = $qrCodeSize;
        $url = $this->BARION_API_URL . BarionClient::API_ENDPOINT_QRCODE;
        return (string)$this->GetFromBarion($url, $model)->Body;
    }

    /* -------- CALLBACK (IPN) VERIFICATION -------- */

    /**
     * Checks that a payment callback (IPN) really comes from Barion: it is signed with the shop's HMAC key,
     * and its timestamp is within 5 minutes of the server time. An unsigned callback is invalid.
     *
     * Without parameters, it checks the current request: the path and query from $_SERVER['REQUEST_URI'],
     * the raw body from php://input, and the signature headers from $_SERVER.
     *
     * @param ?string $requestUri The path and query of the callback, as it was called (e.g. /callback?paymentId=...)
     * @param ?string $body The raw body of the callback
     * @param ?array<string, string> $headers The headers of the callback, with lower case names
     * @param ?callable(string): bool $isNonceNew Optional replay protection: called with the nonce of a correctly
     *  signed callback, it must store the nonce for at least 10 minutes and return false if it was already stored
     *  (e.g. fn($nonce) => apcu_add("barion-nonce-" . $nonce, true, 600)). A callback with a used nonce is invalid.
     * @return bool
     * @throws BarionException
     */
    public function IsCallbackValid(?string $requestUri = null, ?string $body = null, ?array $headers = null, ?callable $isNonceNew = null): bool
    {
        if ($this->HmacKey === null) {
            throw new BarionException("The HMAC key must be set to check the signature of a callback.");
        }

        $requestUri ??= is_string($_SERVER['REQUEST_URI'] ?? null) ? $_SERVER['REQUEST_URI'] : "";
        $body ??= (string)file_get_contents("php://input");
        $headers ??= [
            HmacSignature::HEADER_TIMESTAMP => $_SERVER['HTTP_X_TIMESTAMP'] ?? null,
            HmacSignature::HEADER_NONCE => $_SERVER['HTTP_X_NONCE'] ?? null,
            HmacSignature::HEADER_SIGNATURE => $_SERVER['HTTP_X_SIGNATURE_SHA512'] ?? null
        ];

        $timestamp = $headers[HmacSignature::HEADER_TIMESTAMP] ?? null;
        $nonce = $headers[HmacSignature::HEADER_NONCE] ?? null;
        $signature = $headers[HmacSignature::HEADER_SIGNATURE] ?? null;
        if (!is_string($timestamp) || !is_string($nonce) || !is_string($signature)) {
            return false;
        }

        $payload = HmacSignature::RequestPayload($timestamp, $nonce, "POST", $requestUri, $body);
        // the nonce is only stored for a genuine callback, so forged ones cannot fill the shop's store
        return $this->IsSignedByBarion($payload, $signature)
            && BarionClient::IsTimestampCurrent($timestamp)
            && ($isNonceNew === null || $isNonceNew($nonce) === true);
    }

    /* -------- CURL HTTP REQUEST IMPLEMENTATIONS -------- */

    /**
     * Managing HTTP POST requests
     *
     * @param string $url The URL of the API endpoint
     * @param BaseRequestModel $data The data object to be sent to the endpoint
     * @return HttpResponseModel Returns the response of the API
     *
     * @throws BarionException
     */
    private function PostToBarion(string $url, BaseRequestModel $data): HttpResponseModel
    {
        $ch = curl_init();
        if ($ch === false) {
            throw new BarionException("Failed to initialze cURL module.");
        }

        $posKey = $this->POSKey;

        $userAgent = "";
        if (isset($_SERVER['HTTP_USER_AGENT']) && is_string($_SERVER['HTTP_USER_AGENT'])) {
            $userAgent = $_SERVER['HTTP_USER_AGENT'];
        }
        if ($userAgent == "") {
            /** @var array<string, string> $cver */
            $cver = (array)curl_version();
            $userAgent = "curl/" . $cver["version"] . " " . $cver["ssl_version"];
        }

        $postData = json_encode($data);
        if ($postData === false) {
            $postData = '';
        }

        $httpHeaders = [
            "Content-Type: application/json",
            "User-Agent: " . $userAgent,
            "x-pos-key: $posKey"
        ];

        if ($data->GetIdempotencyKey() != null) {
            array_push($httpHeaders, "Idempotency-Key: " . $data->GetIdempotencyKey());
        }

        $nonce = $this->AddSignatureHeaders($httpHeaders, "POST", $url, $postData);

        /** @var non-empty-string $url */
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $httpHeaders);

        return $this->SendCurlRequest($ch, $nonce);
    }

    /**
     * Managing HTTP GET requests
     *
     * @param string $url The URL of the API endpoint
     * @param BaseRequestModel $data The data object to be sent to the endpoint
     * @return HttpResponseModel Returns the response of the API
     *
     * @throws BarionException
     */
    private function GetFromBarion(string $url, BaseRequestModel $data): HttpResponseModel
    {
        $ch = curl_init();
        if ($ch === false) {
            throw new BarionException("Failed to initialze cURL module.");
        }

        $posKey = $this->POSKey;

        $getData = http_build_query($data);
        $fullUrl = $url . '?' . $getData;

        $userAgent = "";
        if (isset($_SERVER['HTTP_USER_AGENT']) && is_string($_SERVER['HTTP_USER_AGENT'])) {
            $userAgent = $_SERVER['HTTP_USER_AGENT'];
        }
        if ($userAgent == "") {
            /** @var array<string, string> $cver */
            $cver = (array)curl_version();
            $userAgent = "curl/" . $cver["version"] . " " . $cver["ssl_version"];
        }

        $httpHeaders = [
            "User-Agent: " . $userAgent,
            "x-pos-key: $posKey"
        ];

        $nonce = $this->AddSignatureHeaders($httpHeaders, "GET", $fullUrl, "");

        curl_setopt($ch, CURLOPT_URL, $fullUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $httpHeaders);

        return $this->SendCurlRequest($ch, $nonce);
    }

    /**
     * Signs the request if an HMAC key is set
     *
     * @param array<int, string> $httpHeaders The headers of the request, the signature headers are added to these
     * @param string $method The HTTP method
     * @param string $url The full URL of the request
     * @param string $body The exact body sent
     * @return ?string Returns the nonce of the request, or null if the request is not signed
     */
    private function AddSignatureHeaders(array &$httpHeaders, string $method, string $url, string $body): ?string
    {
        if ($this->HmacKey === null) {
            return null;
        }

        $timestamp = (string)time();
        $nonce = HmacSignature::NewNonce();
        $payload = HmacSignature::RequestPayload($timestamp, $nonce, $method, BarionClient::PathAndQuery($url), $body);

        array_push(
            $httpHeaders,
            HmacSignature::HEADER_TIMESTAMP . ": " . $timestamp,
            HmacSignature::HEADER_NONCE . ": " . $nonce,
            HmacSignature::HEADER_SIGNATURE . ": " . HmacSignature::Compute($this->HmacKey, $payload)
        );

        return $nonce;
    }

    /**
     * Returns the path and query of the URL, the way Barion sees it when checking the signature
     *
     * @param string $url
     * @return string
     */
    private static function PathAndQuery(string $url): string
    {
        $path = (string)parse_url($url, PHP_URL_PATH);
        $query = parse_url($url, PHP_URL_QUERY);
        return is_string($query) ? $path . "?" . $query : $path;
    }

    /**
     * @param CurlHandle $ch
     * @param ?string $nonce The nonce of a signed request, null if the request was not signed
     * @return HttpResponseModel
     */
    private function SendCurlRequest(CurlHandle $ch, ?string $nonce = null): HttpResponseModel
    {
        if ($this->UseBundledRootCertificates) {
            curl_setopt($ch, CURLOPT_CAINFO, join(DIRECTORY_SEPARATOR, array(dirname(__FILE__), 'SSL', 'cacert.pem')));

            if ($this->Environment == BarionEnvironment::Test) {
                curl_setopt($ch, CURLOPT_CAPATH, join(DIRECTORY_SEPARATOR, array(dirname(__FILE__), 'SSL', 'gd_bundle-g2.crt')));
            }
        }

        /** @var array<string, string> $responseHeaders */
        $responseHeaders = [];
        curl_setopt($ch, CURLOPT_HEADERFUNCTION, function (CurlHandle $ch, string $header) use (&$responseHeaders): int {
            $parts = explode(":", $header, 2);
            if (count($parts) == 2) {
                $responseHeaders[strtolower(trim($parts[0]))] = trim($parts[1]);
            }
            return strlen($header);
        });

        $rawOutput = curl_exec($ch);
        $output = is_string($rawOutput) ? $rawOutput : '';
        $statusCode = (int)curl_getinfo($ch, CURLINFO_HTTP_CODE);

        $signatureError = $nonce !== null ? $this->CheckResponseSignature($responseHeaders, $nonce, $statusCode, $output) : null;

        if ($signatureError !== null) {
            $response = new BaseResponseModel();
            $response->Errors = array($signatureError);
            $encoded = json_encode($response);
            $output = $encoded !== false ? $encoded : '';
        } elseif ($statusCode == 400 || $statusCode == 409 || $statusCode == 422) {
            $response = json_decode($output);
            if ($response instanceof \stdClass && property_exists($response, "Message")) {
                $errorMessage = $response->Message;
                $error = new ApiErrorModel();
                $error->Description = $errorMessage;
                $response->Errors = array($error);
                $encoded = json_encode($response);
                $output = $encoded !== false ? $encoded : '';
            }
        } else {
            if ($err_nr = curl_errno($ch)) {
                $error = new ApiErrorModel();
                $error->ErrorCode = "CURL_ERROR";
                $error->Title = "CURL Error #" . $err_nr;
                $error->Description = curl_error($ch);

                $response = new BaseResponseModel();
                $response->Errors = array($error);
                $encoded = json_encode($response);
                $output = $encoded !== false ? $encoded : '';
            }
        }
        curl_close($ch);

        $result = new HttpResponseModel();
        $result->StatusCode = $statusCode;
        $result->Body = $output;

        return $result;
    }

    /**
     * Checks the signature and the timestamp of the response to a signed request.
     * Barion signs the response only if the shop's key is active and the request's signature was valid,
     * so an unsigned response is accepted: it is either sent before the key's activation, or a signature error.
     * Barion always signs with its active key, which is the next key once a rotation's new key is activated.
     *
     * @param array<string, string> $responseHeaders The response headers, with lower case names
     * @param string $nonce The nonce of the request
     * @param int $statusCode The HTTP status code of the response
     * @param string $body The raw response body
     * @return ?ApiErrorModel Returns the error the response is discarded for, or null if the response is accepted
     */
    private function CheckResponseSignature(array $responseHeaders, string $nonce, int $statusCode, string $body): ?ApiErrorModel
    {
        if (!isset($responseHeaders[HmacSignature::HEADER_SIGNATURE]) || $this->HmacKey === null) {
            return null;
        }

        $timestamp = $responseHeaders[HmacSignature::HEADER_TIMESTAMP] ?? "";
        $payload = HmacSignature::ResponsePayload($timestamp, $nonce, $statusCode, $body);

        $error = new ApiErrorModel();
        if (!$this->IsSignedByBarion($payload, $responseHeaders[HmacSignature::HEADER_SIGNATURE])) {
            $error->ErrorCode = "InvalidResponseSignature";
            $error->Title = "Invalid response signature";
            $error->Description = "The signature of the Barion API response is invalid, the response was discarded.";
            return $error;
        }

        if (!BarionClient::IsTimestampCurrent($timestamp)) {
            $error->ErrorCode = "InvalidResponseTimestamp";
            $error->Title = "Invalid response timestamp";
            $error->Description = "The timestamp of the Barion API response is more than 5 minutes off the server time, the response was discarded. Please check the clock of your server.";
            return $error;
        }

        return null;
    }

    /**
     * Checks a signature Barion made. Barion always signs with its active key,
     * which is the next key once a rotation's new key is activated.
     *
     * @param string $payload The signed text
     * @param string $signature The received signature (base64 encoded)
     * @return bool
     */
    private function IsSignedByBarion(string $payload, string $signature): bool
    {
        return ($this->HmacKey !== null && HmacSignature::IsValid($this->HmacKey, $payload, $signature))
            || ($this->NextHmacKey !== null && HmacSignature::IsValid($this->NextHmacKey, $payload, $signature));
    }

    /**
     * Checks that a signature's timestamp is within the same tolerance Barion allows for the timestamp of the requests
     *
     * @param string $timestamp Unix time in seconds
     * @return bool
     */
    private static function IsTimestampCurrent(string $timestamp): bool
    {
        return ctype_digit($timestamp) && abs(time() - (int)$timestamp) <= BarionClient::HMAC_TIMESTAMP_TOLERANCE_SECONDS;
    }
}
