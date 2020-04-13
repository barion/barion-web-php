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

namespace Barion;

use Barion\Common\QRCodeSize;
use Barion\Models\Payment\CancelAuthorizationRequestModel;
use Barion\Models\Payment\CaptureRequestModel;
use Barion\Models\Payment\PreparePaymentRequestModel;
use Barion\Models\Payment\FinishReservationRequestModel;
use Barion\Models\Refund\RefundRequestModel;


interface BarionClientInterface
{

    /**
     * Prepare a new payment
     *
     * @param \Barion\Models\Payment\PreparePaymentRequestModel $model
     *   The request model for payment preparation
     *
     * @return \Barion\Models\Payment\PreparePaymentResponseModel
     *   Returns the response from the Barion API/
     */
    public function PreparePayment(PreparePaymentRequestModel $model);

    /**
     * Finish an existing reservation
     *
     * @param \Barion\Models\Payment\FinishReservationRequestModel $model
     *   The request model for the finish process
     *
     * @return \Barion\Models\Payment\FinishReservationResponseModel
     *   Returns the response from the Barion API
     */
    public function FinishReservation(FinishReservationRequestModel $model);

    /**
     * Capture the previously authorized money in a Delayed Capture payment
     *
     * @param \Barion\Models\Payment\CaptureRequestModel $model
     *   The request model for the capture process.
     *
     * @return \Barion\Models\Payment\CaptureResponseModel
     *   Returns the response from the Barion API
     */
    public function Capture(CaptureRequestModel $model);

    /**
     * Cancel a pending authorization on a Delayed Capture payment
     *
     * @param \Barion\Models\Payment\CancelAuthorizationRequestModel $model
     *   The request model for cancelling the authorization.
     *
     * @return \Barion\Models\Payment\CancelAuthorizationResponseModel
     *   Returns the response from the Barion API.
     */
    public function CancelAuthorization(CancelAuthorizationRequestModel $model);

    /**
     * Refund a payment partially or totally
     *
     * @param \Barion\Models\Refund\RefundRequestModel $model
     *   The request model for the refund process/
     *
     * @return \Barion\Models\Refund\RefundResponseModel
     *   Returns the response from the Barion API
     */
    public function RefundPayment(RefundRequestModel $model);

    /**
     * Get detailed information about a given payment
     *
     * @param string $paymentId
     *   The Id of the payment/
     *
     * @return \Barion\Models\Payment\PaymentStateResponseModel
     *   Returns the response from the Barion API.
     */
    public function GetPaymentState($paymentId);

    /**
     * Get the QR code image for a given payment
     *
     * NOTE: This call is deprecated and is only working with username & password authentication.
     * If no username and/or password was set, this method returns NULL.
     *
     * @deprecated
     * @param string $username
     *   The username of the shop's owner
     * @param string $password
     *   The password of the shop's owner
     * @param string $paymentId
     *   The Id of the payment
     * @param string $qrCodeSize
     *   The desired size of the QR image
     *
     * @return mixed|string
     *   Returns the response of the QR request
     */
    public function GetPaymentQRImage($username, $password, $paymentId, $qrCodeSize = QRCodeSize::Large);

}