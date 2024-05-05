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

use Barion\Models\Common\{
    UserModel,
    ItemModel
};

use Barion\Enumerations\{
    Currency,
    TransactionType,
    TransactionStatus
};

class TransactionDetailModel implements \Barion\Interfaces\IBarionModel
{
    public ?string $TransactionId;
    public ?string $POSTransactionId;
    public ?string $TransactionTime;
    public ?float $Total;
    public Currency $Currency;
    public object $Payer;
    public object $Payee;
    public ?string $Comment;
    public TransactionStatus $Status;
    public TransactionType $TransactionType;
    
    /** @var array<object> */
    public array $Items;
    
    public ?string $RelatedId;
    public ?string $POSId;
    public ?string $PaymentId;

    function __construct()
    {
        $this->TransactionId = "";
        $this->POSTransactionId = null;
        $this->TransactionTime = "";
        $this->Total = 0.0;
        $this->Currency = Currency::HUF;
        $this->Payer = new \Barion\Models\Common\UserModel();
        $this->Payee = new \Barion\Models\Common\UserModel();
        $this->Comment = null;
        $this->Status = TransactionStatus::Unknown;
        $this->TransactionType = TransactionType::Unspecified;
        $this->Items = array();
        $this->RelatedId = null;
        $this->POSId = null;
        $this->PaymentId = null;
    }

    public function fromJson(array $json) : void
    {
        if (!empty($json)) {
            $this->TransactionId = JSON::getString($json, 'TransactionId');
            $this->POSTransactionId = JSON::getString($json, 'POSTransactionId');
            $this->TransactionTime = JSON::getString($json, 'TransactionTime');
            $this->Total = JSON::getFloat($json, 'Total');
            $this->Currency = Currency::from(JSON::getString($json, 'Currency') ?? "Unspecified");

            $this->Payer = new UserModel();
            $this->Payer->fromJson(JSON::getArray($json, 'Payer') ?? array());

            $this->Payee = new UserModel();
            $this->Payee->fromJson(JSON::getArray($json, 'Payee') ?? array());

            $this->Comment = JSON::getString($json, 'Comment');
            $this->Status = TransactionStatus::from(JSON::getString($json, 'Status') ?? "Unknown");
            $this->TransactionType = TransactionType::from(JSON::getString($json, 'TransactionType') ?? "Unspecified");

            $this->Items = array();
            
            $items = JSON::getArray($json, 'Items');

            if (!empty($items)) {
                foreach ($items as $key => $i) {
                    $item = new ItemModel();
                    $item->fromJson($i);
                    array_push($this->Items, $item);
                }
            }

            $this->RelatedId = JSON::getString($json, 'RelatedId');
            $this->POSId = JSON::getString($json, 'POSId');
            $this->PaymentId = JSON::getString($json, 'PaymentId');
        }
    }
}