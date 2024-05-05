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

use Barion\Enumerations\{
    PaymentType,
    FundingSourceType,
    Currency
};

class PreparePaymentRequestModel extends \Barion\Models\BaseRequestModel
{
    public string $PaymentType;
    public ?string $ReservationPeriod;
    public ?string $DelayedCapturePeriod;
    public string $PaymentWindow;
    public bool $GuestCheckout;
    public array $FundingSources;
    public string $PaymentRequestId;
    public ?string $PayerHint;
    public array $Transactions;
    public string $Locale;
    public ?string $OrderNumber;
    public ?object $ShippingAddress;
    public ?object $BillingAddress;
    public bool $InitiateRecurrence;
    public ?string $RecurrenceId;
    public ?string $RedirectUrl;
    public ?string $CallbackUrl;
    public string $Currency;
    public ?string $CardHolderNameHint;
    public ?string $PayerPhoneNumber;
    public ?string $PayerWorkPhoneNumber;
    public ?string $PayerHomePhoneNumber;
    public ?object $PayerAccountInformation;
    public ?object $PurchaseInformation;
    public ?string $RecurrenceType;
    public ?string $ChallengePreference;
    public ?string $TraceId;

    function __construct($requestId = "", $type = PaymentType::Immediate, $guestCheckoutAllowed = true, 
                            $allowedFundingSources = array(FundingSourceType::All), $window = "00:30:00", $locale = "hu-HU", 
                            $initiateRecurrence = false, $recurrenceId = null, $redirectUrl = null, 
                            $callbackUrl = null, $currency = Currency::HUF, $traceId = null)
    {
        $this->PaymentRequestId = $requestId;
        $this->PaymentType = $type;
        $this->PaymentWindow = $window;
        $this->GuestCheckout = true;
        $this->FundingSources = array(FundingSourceType::All);
        $this->Locale = $locale;
        $this->InitiateRecurrence = $initiateRecurrence;
        $this->RecurrenceId = $recurrenceId;
        $this->RedirectUrl = $redirectUrl;
        $this->CallbackUrl = $callbackUrl;
        $this->Currency = $currency;
        $this->Transactions = array();
        $this->TraceId = $traceId;
    }

    public function AddTransaction(PaymentTransactionModel $transaction)
    {
        array_push($this->Transactions, $transaction);
    }

    public function AddTransactions($transactions)
    {
        foreach ($transactions as $transaction) {
            if ($transaction instanceof PaymentTransactionModel) {
                $this->AddTransaction($transaction);
            }
        }
    }
}