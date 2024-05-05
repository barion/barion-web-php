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
    PaymentType,
    PaymentStatus,
    Currency,
    RecurrenceResult,
    UILocale
};
use Barion\Enumerations\ThreeDSecure\{
    RecurrenceType
};

class PaymentStateResponseModel extends \Barion\Models\BaseResponseModel implements \Barion\Interfaces\IBarionModel
{
    public ?string $PaymentId;
    public ?string $PaymentRequestId;
    public ?string $OrderNumber;
    public ?string $POSId;
    public ?string $POSName;
    public ?string $POSOwnerEmail;
    public ?string $POSOwnerCountry;
    public PaymentStatus $Status;
    public PaymentType $PaymentType;
    public ?string $FundingSource;
    public object $FundingInformation;
    public ?array $AllowedFundingSources;
    public ?bool $GuestCheckout;
    public ?string $CreatedAt;
    public ?string $ValidUntil;
    public ?string $CompletedAt;
    public ?string $ReservedUntil;
    public ?string $DelayedCaptureUntil;
    public ?float $Total;
    public Currency $Currency;
    public array $Transactions;
    public RecurrenceResult $RecurrenceResult;
    public UILocale $SuggestedLocale;
    public ?float $FraudRiskScore;
    public ?string $RedirectUrl;
    public ?string $CallbackUrl;
    public RecurrenceType $RecurrenceType;
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
        $this->Status = PaymentStatus::Prepared;
        $this->PaymentType = PaymentType::Immediate;
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
        $this->Currency = Currency::HUF;
        $this->Transactions = array();
        $this->RecurrenceResult = RecurrenceResult::None;
        $this->SuggestedLocale = UILocale::HU;
        $this->FraudRiskScore = 0.0;
        $this->RedirectUrl = null;
        $this->CallbackUrl = null;
        $this->TraceId = null;
        $this->RecurrenceType = RecurrenceType::Unspecified;
    }

    public function fromJson(array $json) : void
    {
        if (!empty($json)) {
            parent::fromJson($json);

            $this->PaymentId = JSON::getString($json, 'PaymentId');
            $this->PaymentRequestId = JSON::getString($json, 'PaymentRequestId');
            $this->OrderNumber = JSON::getString($json, 'OrderNumber');
            $this->POSId = JSON::getString($json, 'POSId');
            $this->POSName = JSON::getString($json, 'POSName');
            $this->POSOwnerEmail = JSON::getString($json, 'POSOwnerEmail');
            $this->POSOwnerCountry = JSON::getString($json, 'POSOwnerCountry');
            $this->Status = PaymentStatus::from(JSON::getString($json, 'Status') ?? '');
            $this->PaymentType = PaymentType::from(JSON::getString($json, 'PaymentType') ?? '');
            $this->FundingSource = JSON::getString($json, 'FundingSource');
            if(!empty($json['FundingInformation'])) {
                $this->FundingInformation = new \Barion\Models\Common\FundingInformationModel();
                $this->FundingInformation->fromJson(JSON::getString($json, 'FundingInformation'));
            }
            $this->AllowedFundingSources = JSON::getArray($json, 'AllowedFundingSources');
            $this->GuestCheckout = JSON::getBool($json, 'GuestCheckout');
            $this->CreatedAt = JSON::getString($json, 'CreatedAt');
            $this->ValidUntil = JSON::getString($json, 'ValidUntil');
            $this->CompletedAt = JSON::getString($json, 'CompletedAt');
            $this->ReservedUntil = JSON::getString($json, 'ReservedUntil');
            $this->DelayedCaptureUntil = JSON::getString($json, 'DelayedCaptureUntil');
            $this->Total = JSON::getFloat($json, 'Total');
            $this->Currency = Currency::from(JSON::getString($json, 'Currency') ?? '');
            $this->RecurrenceResult = RecurrenceResult::from(JSON::getString($json, 'RecurrenceResult') ?? 'None');
            $this->SuggestedLocale = UILocale::from(JSON::getString($json, 'SuggestedLocale') ?? '');
            $this->FraudRiskScore = JSON::getFloat($json, 'FraudRiskScore');
            $this->RedirectUrl = JSON::getString($json, 'RedirectUrl');
            $this->CallbackUrl = JSON::getString($json, 'CallbackUrl');
            $this->TraceId = JSON::getString($json, 'TraceId');
            $this->RecurrenceType = RecurrenceType::from(JSON::getString($json, 'RecurrenceType') ?? '');

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
