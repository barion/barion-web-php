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

class PaymentStateResponseModel extends \Barion\Models\BaseResponseModel implements \Barion\Interfaces\IBarionModel
{
    public string $PaymentId;
    public string $PaymentRequestId;
    public ?string $OrderNumber;
    public string $POSId;
    public string $POSName;
    public string $POSOwnerEmail;
    public string $POSOwnerCountry;
    public string $Status;
    public string $PaymentType;
    public ?string $FundingSource;
    public object $FundingInformation;
    public array $AllowedFundingSources;
    public bool $GuestCheckout;
    public string $CreatedAt;
    public ?string $ValidUntil;
    public ?string $CompletedAt;
    public ?string $ReservedUntil;
    public ?string $DelayedCaptureUntil;
    public float $Total;
    public string $Currency;
    public array $Transactions;
    public ?string $RecurrenceResult;
    public string $SuggestedLocale;
    public ?float $FraudRiskScore;
    public ?string $RedirectUrl;
    public ?string $CallbackUrl;
    public ?string $RecurrenceType;
    public ?string $TraceId;

    function __construct()
    {
        parent::__construct();
        $this->PaymentId = "";
        $this->PaymentRequestId = "";
        $this->OrderNumber = null;
        $this->POSId = "";
        $this->POSName = "";
        $this->POSOwnerEmail = "";
        $this->POSOwnerCountry = "";
        $this->Status = "";
        $this->PaymentType = "";
        $this->FundingSource = null;
        $this->FundingInformation = new \Barion\Models\Common\FundingInformationModel();
        $this->AllowedFundingSources = array();
        $this->GuestCheckout = false;
        $this->CreatedAt = "";
        $this->ValidUntil = null;
        $this->CompletedAt = null;
        $this->ReservedUntil = null;
        $this->DelayedCaptureUntil = null;
        $this->Total = 0.0;
        $this->Currency = "";
        $this->Transactions = array();
        $this->RecurrenceResult = "";
        $this->SuggestedLocale ="";
        $this->FraudRiskScore = 0.0;
        $this->RedirectUrl = null;
        $this->CallbackUrl = null;
        $this->TraceId = null;
        $this->RecurrenceType = null;
    }

    public function fromJson($json)
    {
        if (!empty($json)) {
            parent::fromJson($json);

            $this->PaymentId = jget($json, 'PaymentId');
            $this->PaymentRequestId = jget($json, 'PaymentRequestId');
            $this->OrderNumber = jget($json, 'OrderNumber');
            $this->POSId = jget($json, 'POSId');
            $this->POSName = jget($json, 'POSName');
            $this->POSOwnerEmail = jget($json, 'POSOwnerEmail');
            $this->POSOwnerCountry = jget($json, 'POSOwnerCountry');
            $this->Status = jget($json, 'Status');
            $this->PaymentType = jget($json, 'PaymentType');
            $this->FundingSource = jget($json, 'FundingSource');
            if(!empty($json['FundingInformation'])) {
                $this->FundingInformation = new \Barion\Models\Common\FundingInformationModel();
                $this->FundingInformation->fromJson(jget($json, 'FundingInformation'));
            }
            $this->AllowedFundingSources = jget($json, 'AllowedFundingSources');
            $this->GuestCheckout = jget($json, 'GuestCheckout');
            $this->CreatedAt = jget($json, 'CreatedAt');
            $this->ValidUntil = jget($json, 'ValidUntil');
            $this->CompletedAt = jget($json, 'CompletedAt');
            $this->ReservedUntil = jget($json, 'ReservedUntil');
            $this->DelayedCaptureUntil = jget($json, 'DelayedCaptureUntil');
            $this->Total = jget($json, 'Total');
            $this->Currency = jget($json, 'Currency');
            $this->RecurrenceResult = jget($json, 'RecurrenceResult');
            $this->SuggestedLocale = jget($json, 'SuggestedLocale');
            $this->FraudRiskScore = jget($json, 'FraudRiskScore');
            $this->RedirectUrl = jget($json, 'RedirectUrl');
            $this->CallbackUrl = jget($json, 'CallbackUrl');
            $this->TraceId = jget($json, 'TraceId');
            $this->RecurrenceType = jget($json, 'RecurrenceType');

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
