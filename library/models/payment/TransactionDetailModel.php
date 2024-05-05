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

class TransactionDetailModel implements \Barion\Interfaces\IBarionModel
{
    public string $TransactionId;
    public ?string $POSTransactionId;
    public string $TransactionTime;
    public float $Total;
    public string $Currency;
    public object $Payer;
    public object $Payee;
    public ?string $Comment;
    public string $Status;
    public string $TransactionType;
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
        $this->Currency = "";
        $this->Payer = new \Barion\Models\Common\UserModel();
        $this->Payee = new \Barion\Models\Common\UserModel();
        $this->Comment = null;
        $this->Status = "";
        $this->TransactionType = "";
        $this->Items = array();
        $this->RelatedId = null;
        $this->POSId = null;
        $this->PaymentId = null;
    }

    public function fromJson($json)
    {
        if (!empty($json)) {
            $this->TransactionId = $json['TransactionId'];
            $this->POSTransactionId = $json['POSTransactionId'];
            $this->TransactionTime = $json['TransactionTime'];
            $this->Total = $json['Total'];
            $this->Currency = $json['Currency'];

            $this->Payer = new \Barion\Models\Common\UserModel();
            $this->Payer->fromJson($json['Payer']);

            $this->Payee = new \Barion\Models\Common\UserModel();
            $this->Payee->fromJson($json['Payee']);

            $this->Comment = $json['Comment'];
            $this->Status = $json['Status'];
            $this->TransactionType = $json['TransactionType'];

            $this->Items = array();

            if (!empty($json['Items'])) {
                foreach ($json['Items'] as $i) {
                    $item = new \Barion\Models\Common\ItemModel();
                    $item->fromJson($i);
                    array_push($this->Items, $item);
                }
            }

            $this->RelatedId = $json['RelatedId'];
            $this->POSId = $json['POSId'];
            $this->PaymentId = $json['PaymentId'];
        }
    }
}