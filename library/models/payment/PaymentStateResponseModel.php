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

use Barion\Helpers\iBarionModel;
use Barion\Models\Common\FundingInformationModel;
use Barion\Models\BaseResponseModel;

class PaymentStateResponseModel extends BaseResponseModel implements iBarionModel
{
    public $PaymentId;
    public $PaymentRequestId;
    public $OrderNumber;
    public $POSId;
    public $POSName;
    public $POSOwnerEmail;
    public $Status;
    public $PaymentType;
    public $FundingSource;
    public $FundingInformation;
    public $AllowedFundingSources;
    public $GuestCheckout;
    public $CreatedAt;
    public $ValidUntil;
    public $CompletedAt;
    public $ReservedUntil;
    public $Total;
    public $Currency;
    public $Transactions;
    public $RecurrenceResult;
    public $SuggestedLocale;
    public $FraudRiskScore;
    public $RedirectUrl;
    public $CallbackUrl;

    function __construct()
    {
        parent::__construct();
        $this->PaymentId = "";
        $this->PaymentRequestId = "";
        $this->OrderNumber = "";
        $this->POSId = "";
        $this->POSName = "";
        $this->POSOwnerEmail = "";
        $this->Status = "";
        $this->PaymentType = "";
        $this->FundingSource = "";
        $this->FundingInformation = new FundingInformationModel();
        $this->AllowedFundingSources = "";
        $this->GuestCheckout = "";
        $this->CreatedAt = "";
        $this->ValidUntil = "";
        $this->CompletedAt = "";
        $this->ReservedUntil = "";
        $this->Total = 0;
        $this->Currency = "";
        $this->Transactions = array();
        $this->RecurrenceResult = "";
        $this->SuggestedLocale ="";
        $this->FraudRiskScore = 0;
        $this->RedirectUrl = "";
        $this->CallbackUrl = "";
    }

    public function fromJson($json)
    {
        if (!empty($json)) {
            parent::fromJson($json);

            $this->PaymentId = \Barion\Helpers\jget($json, 'PaymentId');
            $this->PaymentRequestId = \Barion\Helpers\jget($json, 'PaymentRequestId');
            $this->OrderNumber = \Barion\Helpers\jget($json, 'OrderNumber');
            $this->POSId = \Barion\Helpers\jget($json, 'POSId');
            $this->POSName = \Barion\Helpers\jget($json, 'POSName');
            $this->POSOwnerEmail = \Barion\Helpers\jget($json, 'POSOwnerEmail');
            $this->Status = \Barion\Helpers\jget($json, 'Status');
            $this->PaymentType = \Barion\Helpers\jget($json, 'PaymentType');
            $this->FundingSource = \Barion\Helpers\jget($json, 'FundingSource');
            if(!empty($json['FundingInformation'])) {
                $this->FundingInformation = new FundingInformationModel();
                $this->FundingInformation->fromJson(Barion\Helpers\jget($json, 'FundingInformation'));
            }
            $this->AllowedFundingSources = \Barion\Helpers\jget($json, 'AllowedFundingSources');
            $this->GuestCheckout = \Barion\Helpers\jget($json, 'GuestCheckout');
            $this->CreatedAt = \Barion\Helpers\jget($json, 'CreatedAt');
            $this->ValidUntil = \Barion\Helpers\jget($json, 'ValidUntil');
            $this->CompletedAt = \Barion\Helpers\jget($json, 'CompletedAt');
            $this->ReservedUntil = \Barion\Helpers\jget($json, 'ReservedUntil');
            $this->Total = \Barion\Helpers\jget($json, 'Total');
            $this->Currency = \Barion\Helpers\jget($json, 'Currency');
            $this->RecurrenceResult = \Barion\Helpers\jget($json, 'RecurrenceResult');
            $this->SuggestedLocale = \Barion\Helpers\jget($json, 'SuggestedLocale');
            $this->FraudRiskScore = \Barion\Helpers\jget($json, 'FraudRiskScore');
            $this->RedirectUrl = \Barion\Helpers\jget($json, 'RedirectUrl');
            $this->CallbackUrl = \Barion\Helpers\jget($json, 'CallbackUrl');

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