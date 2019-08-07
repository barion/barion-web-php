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
class TransactionDetailModel implements iBarionModel
{
    public $TransactionId;
    public $POSTransactionId;
    public $TransactionTime;
    public $Total;
    public $Currency;
    public $Payer;
    public $Payee;
    public $Comment;
    public $Status;
    public $TransactionType;
    public $Items;
    public $RelatedId;
    public $POSId;
    public $PaymentId;

    function __construct()
    {
        $this->TransactionId = "";
        $this->POSTransactionId = "";
        $this->TransactionTime = "";
        $this->Total = 0;
        $this->Currency = "";
        $this->Payer = new UserModel();
        $this->Payee = new UserModel();
        $this->Comment = "";
        $this->Status = "";
        $this->TransactionType = "";
        $this->Items = array();
        $this->RelatedId = "";
        $this->POSId = "";
        $this->PaymentId = "";
    }

    public function fromJson($json)
    {
        if (!empty($json)) {
            $this->TransactionId = $json['TransactionId'];
            $this->POSTransactionId = $json['POSTransactionId'];
            $this->TransactionTime = $json['TransactionTime'];
            $this->Total = $json['Total'];
            $this->Currency = $json['Currency'];

            $this->Payer = new UserModel();
            $this->Payer->fromJson($json['Payer']);

            $this->Payee = new UserModel();
            $this->Payee->fromJson($json['Payee']);

            $this->Comment = $json['Comment'];
            $this->Status = $json['Status'];
            $this->TransactionType = $json['TransactionType'];

            $this->Items = array();

            if (!empty($json['Items'])) {
                foreach ($json['Items'] as $i) {
                    $item = new ItemModel();
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