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

use Barion\Helpers\JSON;

use Barion\Enumerations\{
    PaymentStatus
};

class FinishReservationResponseModel extends \Barion\Models\BaseResponseModel implements \Barion\Interfaces\IBarionModel
{
    public ?bool $IsSuccessful;
    public ?string $PaymentId;
    public ?string $PaymentRequestId;
    public PaymentStatus $Status;
    public array $Transactions;

    function __construct()
    {
        parent::__construct();
        $this->IsSuccessful = false;
        $this->PaymentId = "";
        $this->PaymentRequestId = "";
        $this->Status = PaymentStatus::Prepared;
        $this->Transactions = array();
    }

    public function fromJson(array $json) : void
    {
        if (!empty($json)) {
            parent::fromJson($json);

            $this->IsSuccessful = JSON::getBool($json, 'IsSuccessful');
            $this->PaymentId = JSON::getString($json, 'PaymentId');
            $this->PaymentRequestId = JSON::getString($json, 'PaymentRequestId');
            $this->Status = PaymentStatus::from(JSON::getString($json, 'Status') ?? '');

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