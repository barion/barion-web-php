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
class PaymentStateResponseModel extends BaseResponseModel implements iBarionModel
{
    public $PaymentId;
    public $PaymentRequestId;
    public $POSId;
    public $POSName;
    public $Status;
    public $PaymentType;
    public $FundingSource;
    public $AllowedFundingSources;
    public $GuestCheckout;
    public $CreatedAt;
    public $ValidUntil;
    public $CompletedAt;
    public $ReservedUntil;
    public $Total;
    public $Transactions;
    public $RecurrenceResult;

    function __construct()
    {
        parent::__construct();
        $this->PaymentId = "";
        $this->PaymentRequestId = "";
        $this->POSId = "";
        $this->POSName = "";
        $this->Status = "";
        $this->PaymentType = "";
        $this->FundingSource = "";
        $this->AllowedFundingSources = "";
        $this->GuestCheckout = "";
        $this->CreatedAt = "";
        $this->ValidUntil = "";
        $this->CompletedAt = "";
        $this->ReservedUntil = "";
        $this->Total = 0;
        $this->Transactions = array();
        $this->RecurrenceResult = "";
    }

    public function fromJson($json)
    {
        if (!empty($json)) {
            parent::fromJson($json);

            $this->PaymentId = jget($json, 'PaymentId');
            $this->PaymentRequestId = jget($json, 'PaymentRequestId');
            $this->Status = jget($json, 'Status');
            $this->PaymentType = jget($json, 'PaymentType');
            $this->FundingSource = jget($json, 'FundingSource');
            $this->GuestCheckout = jget($json, 'GuestCheckout');
            $this->CreatedAt = jget($json, 'CreatedAt');
            $this->ValidUntil = jget($json, 'ValidUntil');
            $this->CompletedAt = jget($json, 'CompletedAt');
            $this->ReservedUntil = jget($json, 'ReservedUntil');
            $this->Total = jget($json, 'Total');
            $this->AllowedFundingSources = jget($json, 'AllowedFundingSources');
            $this->RecurrenceResult = jget($json, 'RecurrenceResult');

            $this->Transactions = array();

            if (!empty($json['Transactions'])) {
                foreach ($json['Transactions'] as $key => $value) {
                    $tr = new TransactionDetailModel();
                    $tr->fromJson($value);
                    array_push($this->Transactions, $tr);
                }
            }
        }
    }
}