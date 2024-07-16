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
    PaymentStatus
};

/**
 * Model containing the response data after requesting the finishing of a previously started reservation payment on the Barion Smart Gateway.
 */
class FinishReservationResponseModel extends BaseResponseModel implements IBarionModel
{
    /** 
     * Flag indicating that the cancellation was successful.
     * 
     * @var ?bool
     */
    public ?bool $IsSuccessful;

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
     * The status of the payment
     * 
     * @var PaymentStatus
     */
    public PaymentStatus $Status;
    
    /**
     * Array of reservation payment transactions that were finished.
     * 
     *  @var array<object> 
    */
    public array $Transactions;

    function __construct()
    {
        parent::__construct();
        $this->IsSuccessful = false;
        $this->PaymentId = "";
        $this->PaymentRequestId = "";
        $this->Transactions = array();
    }

    public function fromJson(array $json) : void
    {
        if (!empty($json)) {
            parent::fromJson($json);

            $this->IsSuccessful = JSON::getBool($json, 'IsSuccessful');
            $this->PaymentId = JSON::getString($json, 'PaymentId');
            $this->PaymentRequestId = JSON::getString($json, 'PaymentRequestId');

            if (array_key_exists('Status', $json)) {
                $this->Status = PaymentStatus::from(JSON::getString($json, 'Status') ?? 'Prepared');
            }
            
            $this->Transactions = array();
            
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