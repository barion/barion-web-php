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

namespace Barion\Models\Payment;

use Barion\Interfaces\IBarionModel;
use Barion\Models\BaseResponseModel;
use Barion\Helpers\JSON;
use Barion\Enumerations\{
    PaymentStatus,
    RecurrenceResult
};

/**
 * Model containing the response data after starting a new payment on the Barion Smart Gateway.
 */
class PreparePaymentResponseModel extends BaseResponseModel implements IBarionModel
{
    /** 
     * The Barion identifier of the payment.
     * 
     * @var ?string
     */
    public ?string $PaymentId;

    /** 
     * The internal identifier of the payment, specified by the shop.
     * 
     * @var ?string
     */
    public ?string $PaymentRequestId;

    /** 
     * The status of the payment.
     * 
     * @var PaymentStatus
     */
    public PaymentStatus $Status;
    
    /** 
     * Array of payment transactions included in the payment.
     * 
     * @var array<object> 
    */
    public array $Transactions;

    /** 
     * URL for a QR code image containing the Barion Smart Gateway address for the payment.
     * 
     * @var ?string
     */
    public ?string $QRUrl;

    /** 
     * The result of a recurring payment action, if applicable.
     * 
     * @var RecurrenceResult
     */
    public RecurrenceResult $RecurrenceResult;

    /** 
     * The Barion Smart Gateway URL the customer should be redirected to, so they can complete the payment.
     * 
     * @var ?string
     */
    public ?string $PaymentRedirectUrl;

    /** 
     * 3D-Secure authentication data, applicable if off-site authentication is required.
     * 
     * @var ?string
     */
    public ?string $ThreeDSAuthClientData;

    /** 
     * The trace id of the 3D-Secure payment flow.
     * 
     * @var ?string
     */    
    public ?string $TraceId;

    function __construct()
    {
        parent::__construct();
        $this->PaymentId = null;
        $this->PaymentRequestId = null;
        $this->QRUrl = null;
        $this->RecurrenceResult = RecurrenceResult::None;
        $this->PaymentRedirectUrl = null;
        $this->ThreeDSAuthClientData = null;
        $this->TraceId = null;
        $this->Transactions = array();
    }

    public function fromJson(array $json) : void
    {
        if (!empty($json)) {
            parent::fromJson($json);
            $this->PaymentId = JSON::getString($json, 'PaymentId');
            $this->PaymentRequestId = JSON::getString($json, 'PaymentRequestId');
            $this->QRUrl = JSON::getString($json, 'QRUrl');
            $this->RecurrenceResult = RecurrenceResult::from(JSON::getString($json, 'RecurrenceResult') ?? 'None');
            $this->ThreeDSAuthClientData = JSON::getString($json, 'ThreeDSAuthClientData');
            $this->TraceId = JSON::getString($json, 'TraceId');
            $this->Transactions = array();

            if (array_key_exists('Status', $json)) {
                $this->Status = PaymentStatus::from(JSON::getString($json, 'Status') ?? 'Prepared');
            }
            
            $transactions = JSON::getArray($json, 'Transactions');

            if (!empty($transactions)) {
                foreach ($transactions as $key => $transaction) {
                    $tr = new TransactionResponseModel();
                    $tr->fromJson($transaction);
                    $this->Transactions[] = $tr;
                }
            }

        }
    }
}