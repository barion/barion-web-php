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

namespace Barion\Models\Payment;

use function Barion\Helpers\jget;
use Barion\Enumerations\{
    PaymentStatus,
    RecurrenceResult
};

class PreparePaymentResponseModel extends \Barion\Models\BaseResponseModel implements \Barion\Interfaces\IBarionModel
{
    public ?string $PaymentId;
    public ?string $PaymentRequestId;
    public PaymentStatus $Status;
    public array $Transactions;
    public ?string $QRUrl;
    public RecurrenceResult $RecurrenceResult;
    public ?string $PaymentRedirectUrl;
    public ?string $ThreeDSAuthClientData;
    public ?string $TraceId;

    function __construct()
    {
        parent::__construct();
        $this->PaymentId = null;
        $this->PaymentRequestId = null;
        $this->Status = PaymentStatus::Prepared;
        $this->QRUrl = null;
        $this->RecurrenceResult = RecurrenceResult::None;
        $this->PaymentRedirectUrl = null;
        $this->ThreeDSAuthClientData = null;
        $this->TraceId = null;
        $this->Transactions = array();
    }

    public function fromJson($json)
    {
        if (!empty($json)) {
            parent::fromJson($json);
            $this->PaymentId = jget($json, 'PaymentId');
            $this->PaymentRequestId = jget($json, 'PaymentRequestId');
            $this->Status = PaymentStatus::from(jget($json, 'Status') ?? '');
            $this->QRUrl = jget($json, 'QRUrl');
            $this->RecurrenceResult = RecurrenceResult::from(jget($json, 'RecurrenceResult') ?? 'None');
            $this->ThreeDSAuthClientData = jget($json, 'ThreeDSAuthClientData');
            $this->TraceId = jget($json, 'TraceId');
            $this->Transactions = array();

            if (!empty($json['Transactions'])) {
                foreach ($json['Transactions'] as $key => $value) {
                    $tr = new TransactionResponseModel();
                    $tr->fromJson($value);
                    array_push($this->Transactions, $tr);
                }
            }

        }
    }
}