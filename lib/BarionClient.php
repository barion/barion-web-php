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
*  
*  BarionClient.php
*  PHP library for implementing REST API calls towards the Barion payment system.  
*  
*/

include 'helpers' . DIRECTORY_SEPARATOR . 'loader.php';

class BarionClient
{
    private $Environment;

    private $Password;
    private $APIVersion;
    private $POSKey;

    private $BARION_API_URL = "";
    private $BARION_WEB_URL = "";

    private $UseBundledRootCertificates;

    /**
     *  Constructor
     *
     * @param $poskey The secret POSKey of your shop
     * @param int $version The version of the Barion API
     * @param string $env The environment to connect to
     * @param bool $useBundledRootCerts Set this to true if you're having problem with SSL connection
     */
    function __construct($poskey, $version = 2, $env = BarionEnvironment::Prod, $useBundledRootCerts = false)
    {

        $this->POSKey = $poskey;
        $this->APIVersion = $version;
        $this->Environment = $env;

        switch ($env) {

            case BarionEnvironment::Test:
                $this->BARION_API_URL = BARION_API_URL_TEST;
                $this->BARION_WEB_URL = BARION_WEB_URL_TEST;
                break;

            case BarionEnvironment::Prod:
            default:
                $this->BARION_API_URL = BARION_API_URL_PROD;
                $this->BARION_WEB_URL = BARION_WEB_URL_PROD;
                break;
        }

        $this->UseBundledRootCertificates = $useBundledRootCerts;
    }

    /* -------- BARION API CALL IMPLEMENTATIONS -------- */


    /**
     * Prepare a new payment
     *
     * @param PreparePaymentRequestModel $model The request model for payment preparation
     * @return PreparePaymentResponseModel Returns the response from the Barion API
     */
    public function PreparePayment(PreparePaymentRequestModel $model)
    {
        $model->POSKey = $this->POSKey;
        $url = $this->BARION_API_URL . "/v" . $this->APIVersion . API_ENDPOINT_PREPAREPAYMENT;
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
     */
    public function FinishReservation(FinishReservationRequestModel $model)
    {
        $model->POSKey = $this->POSKey;
        $url = $this->BARION_API_URL . "/v" . $this->APIVersion . API_ENDPOINT_FINISHRESERVATION;
        $response = $this->PostToBarion($url, $model);
        $rm = new FinishReservationResponseModel();
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
     */
    public function RefundPayment(RefundRequestModel $model)
    {
        $model->POSKey = $this->POSKey;
        $url = $this->BARION_API_URL . "/v" . $this->APIVersion . API_ENDPOINT_REFUND;
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
     */
    public function GetPaymentState($paymentId)
    {
        $model = new PaymentStateRequestModel($paymentId);
        $model->POSKey = $this->POSKey;
        $url = $this->BARION_API_URL . "/v" . $this->APIVersion . API_ENDPOINT_PAYMENTSTATE;
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
     * @param $username The username of the shop's owner
     * @param $password The password of the shop's owner
     * @param $paymentId The Id of the payment
     * @param string $qrCodeSize The desired size of the QR image
     * @return mixed|string Returns the response of the QR request
     */
    public function GetPaymentQRImage($username, $password, $paymentId, $qrCodeSize = QRCodeSize::Large)
    {
        $model = new PaymentQRRequestModel($paymentId);
        $model->POSKey = $this->POSKey;
        $model->UserName = $username;
        $model->Password = $password;
        $model->Size = $qrCodeSize;
        $url = $this->BARION_API_URL . API_ENDPOINT_QRCODE;
        $response = $this->GetFromBarion($url, $model);
        return $response;
    }

    /* -------- CURL HTTP REQUEST IMPLEMENTATIONS -------- */

    /*
    *
    */
    /**
     * Managing HTTP POST requests
     *
     * @param $url The URL of the API endpoint
     * @param $data The data object to be sent to the endpoint
     * @return mixed|string Returns the response of the API
     */
    private function PostToBarion($url, $data)
    {
        $ch = curl_init();

        $postData = json_encode($data);

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json"));

        if ($this->UseBundledRootCertificates) {
            curl_setopt($ch, CURLOPT_CAINFO, join(DIRECTORY_SEPARATOR, array(dirname(__FILE__), 'ssl', 'cacert.pem')));

            if ($this->Environment == BarionEnvironment::Test) {
                curl_setopt($ch, CURLOPT_CAPATH, join(DIRECTORY_SEPARATOR, array(dirname(__FILE__), 'ssl', 'gd_bundle-g2.crt')));
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
     * @param $url The URL of the API endpoint
     * @param $data The data object to be sent to the endpoint
     * @return mixed|string Returns the response of the API
     */
    private function GetFromBarion($url, $data)
    {
        $ch = curl_init();

        $getData = http_build_query($data);
        $fullUrl = $url . '?' . $getData;

        curl_setopt($ch, CURLOPT_URL, $fullUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        if ($this->UseBundledRootCertificates) {
            curl_setopt($ch, CURLOPT_CAINFO, join(DIRECTORY_SEPARATOR, array(dirname(__FILE__), 'ssl', 'cacert.pem')));

            if ($this->Environment == BarionEnvironment::Test) {
                curl_setopt($ch, CURLOPT_CAPATH, join(DIRECTORY_SEPARATOR, array(dirname(__FILE__), 'ssl', 'gd_bundle-g2.crt')));
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