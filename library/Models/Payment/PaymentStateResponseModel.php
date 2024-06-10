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
use Barion\Models\Common\FundingInformationModel;
use Barion\Enumerations\{FundingSourceType, PaymentType, PaymentStatus, Currency, UILocale};
use Barion\Enumerations\ThreeDSecure\{
    RecurrenceType
};

/**
 * Model containing detailed information about a payment on the Barion Smart Gateway.
 */
class PaymentStateResponseModel extends BaseResponseModel implements IBarionModel
{
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
     * The order number of the payment, specified by the shop.
     * 
     * @var ?string
     */
    public ?string $OrderNumber;

    /** 
     * The Barion identifier of the shop that started the payment.
     * 
     * @var ?string
     */
    public ?string $POSId;

    /** 
     * The name of the shop that started the payment.
     * 
     * @var ?string
     */
    public ?string $POSName;

    /** 
     * The e-mail address of the owner wallet of the shop that started the payment.
     * 
     * @var ?string
     */
    public ?string $POSOwnerEmail;

    /** 
     * The country of the owner wallet of the shop that started the payment.
     * 
     * @var ?string
     */
    public ?string $POSOwnerCountry;

    /** 
     * The current status of the payment.
     * 
     * @var PaymentStatus
     */
    public PaymentStatus $Status;

    /** 
     * The type of the payment.
     * 
     * @var PaymentType
     */
    public PaymentType $PaymentType;

    /** 
     * The funding source used to complete the payment, if applicable.
     * 
     * @var ?string
     */
    public ?string $FundingSource;

    /** 
     * Detailed information about the funding source used to attempt to complete the payment, if applicable.
     * 
     * @var object
     */
    public object $FundingInformation;
    
    /** 
     * List of funding source types allowed to complete the payment.
     * 
     * @var array<FundingSourceType>
    */
    public ?array $AllowedFundingSources;
    
    /** 
     * Flag indicating if guest checkout is allowed for the payment.
     * 
     * @var ?bool
     */
    public ?bool $GuestCheckout;

    /** 
     * ISO-8601 format timestamp of when the payment was created.
     * 
     * @var ?string
     */
    public ?string $CreatedAt;

    /** 
     * ISO-8601 format timestamp while the payment can be completed.
     * 
     * @var ?string
     */
    public ?string $ValidUntil;

    /** 
     * ISO-8601 format timestamp of when the payment was completed.
     * 
     * @var ?string
     */
    public ?string $CompletedAt;

    /** 
     * ISO-8601 format timestamp while a started reservation payment can be finished.
     * 
     * @var ?string
     */
    public ?string $ReservedUntil;

    /** 
     * ISO-8601 format timestamp while an authorized delayed capture payment can be captured.
     * 
     * @var ?string
     */
    public ?string $DelayedCaptureUntil;

    /** 
     * Total amount of the payment.
     * 
     * @var ?float
     */
    public ?float $Total;

    /** 
     * The currency of the payment.
     * 
     * @var Currency
     */
    public Currency $Currency;
    
    /** 
     * Array of payment transactions attached to the payment.
     * 
     * @var array<object> 
    */
    public array $Transactions;

    /** 
     * The locale of the Barion Smart Gateway for the payment.
     * 
     * @var UILocale
     */
    public UILocale $SuggestedLocale;

    /** 
     * The fraud risk score connected to the payment, if applicable.
     * 
     * @var ?float
     */
    public ?float $FraudRiskScore;

    /** 
     * The URL where the customer is redirected after completing or rejecting the payment.
     * 
     * @var ?string
     */
    public ?string $RedirectUrl;

    /** 
     * The URL where the Barion system will send a callback request whenever the payment state is changed.
     * 
     * @var ?string
     */
    public ?string $CallbackUrl;

    /** 
     * The recurrence type of the payment, if applicable.
     * 
     * @var ?string
     */
    public ?string $RecurrenceType;

    /** 
     * The trace id of the 3D-Secure payment flow.
     * 
     * @var ?string
     */
    public ?string $TraceId;

    /** 
     * The payment method that was ultimately used to complete the payment, if applicable
     * 
     * @var ?string
     */
    public ?string $PaymentMethod;

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
        $this->FundingSource = null;
        $this->FundingInformation = new FundingInformationModel();
        $this->AllowedFundingSources = array();
        $this->GuestCheckout = false;
        $this->CreatedAt = "";
        $this->ValidUntil = null;
        $this->CompletedAt = null;
        $this->ReservedUntil = null;
        $this->DelayedCaptureUntil = null;
        $this->Total = 0.0;
        $this->Transactions = array();
        $this->FraudRiskScore = 0.0;
        $this->RedirectUrl = null;
        $this->CallbackUrl = null;
        $this->TraceId = null;
        $this->RecurrenceType = null;
        $this->PaymentMethod = null;
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
            if (array_key_exists('Status', $json)) {
                $this->Status = PaymentStatus::from(JSON::getString($json, 'Status') ?? 'Prepared');
            }
            if (array_key_exists('PaymentType', $json)) {
                $this->PaymentType = PaymentType::from(JSON::getString($json, 'PaymentType') ?? 'Immediate');
            }
            $this->FundingSource = JSON::getString($json, 'FundingSource');
            
            $fundingInformation = JSON::getArray($json, 'FundingInformation');
            if(!empty($fundingInformation)) {
                $this->FundingInformation = new FundingInformationModel();
                $this->FundingInformation->fromJson($fundingInformation);
            }
            
            $this->AllowedFundingSources = JSON::getArray($json, 'AllowedFundingSources');
            $this->GuestCheckout = JSON::getBool($json, 'GuestCheckout');
            $this->CreatedAt = JSON::getString($json, 'CreatedAt');
            $this->ValidUntil = JSON::getString($json, 'ValidUntil');
            $this->CompletedAt = JSON::getString($json, 'CompletedAt');
            $this->ReservedUntil = JSON::getString($json, 'ReservedUntil');
            $this->DelayedCaptureUntil = JSON::getString($json, 'DelayedCaptureUntil');
            $this->Total = JSON::getFloat($json, 'Total');
            
            if (array_key_exists('Currency', $json)) {
                $this->Currency = Currency::from(JSON::getString($json, 'Currency') ?? 'HUF');
            }

            if (array_key_exists('SuggestedLocale', $json)) {
                $this->SuggestedLocale = UILocale::from(JSON::getString($json, 'SuggestedLocale') ?? 'HU');
            }
            
            $this->FraudRiskScore = JSON::getFloat($json, 'FraudRiskScore');
            $this->RedirectUrl = JSON::getString($json, 'RedirectUrl');
            $this->CallbackUrl = JSON::getString($json, 'CallbackUrl');
            $this->TraceId = JSON::getString($json, 'TraceId');

            if (array_key_exists("RecurrenceType", $json) && $json["RecurrenceType"] !== null) {
                $this->RecurrenceType = RecurrenceType::from(JSON::getString($json, 'RecurrenceType') ?? 'MerchantInitiatedPayment')->value;
            }

            $this->PaymentMethod = JSON::getString($json, 'PaymentMethod');

            $this->Transactions = array();
            
            $transactions = JSON::getArray($json, 'Transactions');

            if (!empty($transactions)) {
                foreach ($transactions as $key => $transaction) {
                    $tr = new TransactionDetailModel();
                    $tr->fromJson($transaction);
                    $this->Transactions[] = $tr;
                }
            }

        }
    }
}
