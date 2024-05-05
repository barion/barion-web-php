<?php

/**
 * Copyright 2016 Barion Payment Inc. All Rights Reserved.
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
use Barion\Models\{
    BaseResponseModel
};
use Barion\Models\Error\{
    ApiErrorModel
};
use Barion\Models\Payment\{
    PreparePaymentRequestModel,
    PreparePaymentResponseModel,
    FinishReservationRequestModel,
    FinishReservationResponseModel,
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

    public const BARION_API_URL_PROD               = "https://api.barion.com";
    public const BARION_WEB_URL_PROD               = "https://secure.barion.com/Pay";
    public const BARION_API_URL_TEST               = "https://api.test.barion.com";
    public const BARION_WEB_URL_TEST               = "https://secure.test.barion.com/Pay";

    public const API_ENDPOINT_PREPAREPAYMENT       = "/Payment/Start";
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
    public function SetVersion(int $version) {
        $this->APIVersion = $version;
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
    public function PreparePayment(PreparePaymentRequestModel $model)
    {
        if ($this->APIVersion != 2) {
            throw new BarionException("Incorrect API version for Payment Prepare endpoint! Current: {$this->APIVersion}. Expected: 2.");
        }

        $model->POSKey = $this->POSKey;
        $url = $this->BARION_API_URL . "/v" . $this->APIVersion . BarionClient::API_ENDPOINT_PREPAREPAYMENT;
        $response = $this->PostToBarion($url, $model);
        $rm = new PreparePaymentResponseModel();
        if (!empty($response)) {
            $json = json_decode($response, true);
            $rm->fromJson($json);
            if (!empty($rm->PaymentId)) {
                $rm->PaymentRedirectUrl = $this->BARION_WEB_URL . "?" . http_build_query(array("id" => $rm->PaymentId));
            }
        }
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
    public function FinishReservation(FinishReservationRequestModel $model)
    {
        if ($this->APIVersion != 2) {
            throw new BarionException("Incorrect API version for Finish Reservation endpoint! Current: {$this->APIVersion}. Expected: 2.");
        }

        $model->POSKey = $this->POSKey;
        $url = $this->BARION_API_URL . "/v" . $this->APIVersion . BarionClient::API_ENDPOINT_FINISHRESERVATION;
        $response = $this->PostToBarion($url, $model);
        $rm = new FinishReservationResponseModel();
        if (!empty($response)) {
            $json = json_decode($response, true);
            $rm->fromJson($json);
        }
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
    public function Capture(CaptureRequestModel $model)
    {
        if ($this->APIVersion != 2) {
            throw new BarionException("Incorrect API version for Capture endpoint! Current: {$this->APIVersion}. Expected: 2.");
        }

        $model->POSKey = $this->POSKey;
        $url = $this->BARION_API_URL . "/v" . $this->APIVersion . BarionClient::API_ENDPOINT_CAPTURE;
        $response = $this->PostToBarion($url, $model);
        $captureResponse = new CaptureResponseModel();
        if (!empty($response)) {
            $json = json_decode($response, true);
            $captureResponse->fromJson($json);
        }
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
    public function CancelAuthorization(CancelAuthorizationRequestModel $model)
    {
        if ($this->APIVersion != 2) {
            throw new BarionException("Incorrect API version for Cancel Authorization endpoint! Current: {$this->APIVersion}. Expected: 2.");
        }

        $model->POSKey = $this->POSKey;
        $url = $this->BARION_API_URL . "/v" . $this->APIVersion . BarionClient::API_ENDPOINT_CANCELAUTHORIZATION;
        $response = $this->PostToBarion($url, $model);
        $cancelAuthResponse = new CancelAuthorizationResponseModel();
        if (!empty($response)) {
            $json = json_decode($response, true);
            $cancelAuthResponse->fromJson($json);
        }
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
    public function Complete3DSPayment(Complete3DSPaymentRequestModel $model)
    {
        if ($this->APIVersion != 2) {
            throw new BarionException("Incorrect API version for 3DS Complete endpoint! Current: {$this->APIVersion}. Expected: 2.");
        }

        $model->POSKey = $this->POSKey;
        $url = $this->BARION_API_URL . "/v" . $this->APIVersion . BarionClient::API_ENDPOINT_3DS_COMPLETE;
        $response = $this->PostToBarion($url, $model);
        $rm = new Complete3DSPaymentResponseModel();
        if (!empty($response)) {
            $json = json_decode($response, true);
            $rm->fromJson($json);
        }
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
    public function RefundPayment(RefundRequestModel $model)
    {
        if ($this->APIVersion != 2) {
            throw new BarionException("Incorrect API version for Refund endpoint! Current: {$this->APIVersion}. Expected: 2.");
        }

        $model->POSKey = $this->POSKey;
        $url = $this->BARION_API_URL . "/v" . $this->APIVersion . BarionClient::API_ENDPOINT_REFUND;
        $response = $this->PostToBarion($url, $model);
        $rm = new RefundResponseModel();
        if (!empty($response)) {
            $json = json_decode($response, true);
            $rm->fromJson($json);
        }
        return $rm;
    }

    /**
     * Get detailed information about a given payment
     *
     * @param string $paymentId The Id of the payment
     * @return PaymentStateResponseModel Returns the response from the Barion API
     * 
     * @throws BarionException
     */
    public function GetPaymentState($paymentId)
    {
        if ($this->APIVersion != 4) {
            throw new BarionException("Incorrect API version for PaymentState endpoint! Current: {$this->APIVersion}. Expected: 4.");
        }

        $model = new PaymentStateRequestModel($paymentId);
        $model->POSKey = $this->POSKey;
        $url = $this->BARION_API_URL . "/v" . $this->APIVersion . str_ireplace("{paymentId}", $paymentId, BarionClient::API_ENDPOINT_PAYMENTSTATE);
        $response = $this->GetFromBarion($url, $model);
        $ps = new PaymentStateResponseModel();
        if (!empty($response)) {
            $json = json_decode($response, true);
            $ps->fromJson($json);
        }
        return $ps;
    }

    /**
     * Get the QR code image for a given payment
     *
     * NOTE: This call is deprecated and is only working with username & password authentication.
     * If no username and/or password was set, this method returns NULL.
     *
     * @deprecated
     * @param string $username The username of the shop's owner
     * @param string $password The password of the shop's owner
     * @param string $paymentId The Id of the payment
     * @param QRCodeSize $qrCodeSize The desired size of the QR image
     * @return mixed|string Returns the response of the QR request
     * 
     * @throws BarionException
     */
    public function GetPaymentQRImage($username, $password, $paymentId, $qrCodeSize = QRCodeSize::Large)
    {
        if ($this->APIVersion != 1) {
            throw new BarionException("Incorrect API version for QR Code endpoint! Current: {$this->APIVersion}. Expected: 1.");
        }

        $model = new PaymentQRRequestModel($username, $password, $paymentId);
        $model->POSKey = $this->POSKey;
        $model->Size = $qrCodeSize;
        $url = $this->BARION_API_URL . BarionClient::API_ENDPOINT_QRCODE;
        $response = $this->GetFromBarion($url, $model);
        return $response;
    }

    /* -------- CURL HTTP REQUEST IMPLEMENTATIONS -------- */

    /**
     * Managing HTTP POST requests
     *
     * @param string $url The URL of the API endpoint
     * @param object $data The data object to be sent to the endpoint
     * @return mixed|string Returns the response of the API
     */
    private function PostToBarion($url, $data)
    {
        $ch = curl_init();
        $posKey = $this->POSKey;
        
        $userAgent = $_SERVER['HTTP_USER_AGENT'];
        if ($userAgent == "") {
            $cver = (array)curl_version();
            $userAgent = "curl/" . $cver["version"] . " " .$cver["ssl_version"];
        }

        $postData = json_encode($data);

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "Content-Type: application/json", 
            "User-Agent: $userAgent",
            "x-pos-key: $posKey"
        ]);
        
        if ($this->UseBundledRootCertificates) {
            curl_setopt($ch, CURLOPT_CAINFO, join(DIRECTORY_SEPARATOR, array(dirname(__FILE__), 'SSL', 'cacert.pem')));

            if ($this->Environment == BarionEnvironment::Test) {
                curl_setopt($ch, CURLOPT_CAPATH, join(DIRECTORY_SEPARATOR, array(dirname(__FILE__), 'SSL', 'gd_bundle-g2.crt')));
            }
        }

        $output = curl_exec($ch);
        if ($err_nr = curl_errno($ch)) {
            $error = new ApiErrorModel();
            $error->ErrorCode = "CURL_ERROR";
            $error->Title = "CURL Error #" . $err_nr;
            $error->Description = curl_error($ch);

            $response = new BaseResponseModel();
            $response->Errors = array($error);
            $output = json_encode($response);
        }
        curl_close($ch);

        return $output;
    }

    /**
     * Managing HTTP GET requests
     *
     * @param string $url The URL of the API endpoint
     * @param object $data The data object to be sent to the endpoint
     * @return mixed|string Returns the response of the API
     */
    private function GetFromBarion($url, $data)
    {
        $ch = curl_init();
        $posKey = $this->POSKey;

        $getData = http_build_query($data);
        $fullUrl = $url . '?' . $getData;
        
        $userAgent = $_SERVER['HTTP_USER_AGENT'];
        if ($userAgent == "") {
            $cver = (array)curl_version();
            $userAgent = "curl/" . $cver["version"] . " " .$cver["ssl_version"];
        }

        curl_setopt($ch, CURLOPT_URL, $fullUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "User-Agent: $userAgent",
            "x-pos-key: $posKey"
        ]);

        if ($this->UseBundledRootCertificates) {
            curl_setopt($ch, CURLOPT_CAINFO, join(DIRECTORY_SEPARATOR, array(dirname(__FILE__), 'SSL', 'cacert.pem')));

            if ($this->Environment == BarionEnvironment::Test) {
                curl_setopt($ch, CURLOPT_CAPATH, join(DIRECTORY_SEPARATOR, array(dirname(__FILE__), 'SSL', 'gd_bundle-g2.crt')));
            }
        }

        $output = curl_exec($ch);
        if ($err_nr = curl_errno($ch)) {
            $error = new ApiErrorModel();
            $error->ErrorCode = "CURL_ERROR";
            $error->Title = "CURL Error #" . $err_nr;
            $error->Description = curl_error($ch);

            $response = new BaseResponseModel();
            $response->Errors = array($error);
            $output = json_encode($response);
        }
        curl_close($ch);

        return $output;
    }
}