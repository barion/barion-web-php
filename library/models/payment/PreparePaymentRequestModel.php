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
class PreparePaymentRequestModel extends BaseRequestModel
{
    public $PaymentType;
    public $ReservationPeriod;
    public $DelayedCapturePeriod;
    public $PaymentWindow;
    public $GuestCheckout;
    public $FundingSources;
    public $PaymentRequestId;
    public $PayerHint;
    public $Transactions;
    public $Locale;
    public $OrderNumber;
    public $ShippingAddress;
    public $BillingAddress;
    public $InitiateRecurrence;
    public $RecurrenceId;
    public $RedirectUrl;
    public $CallbackUrl;
    public $Currency;
    public $CardHolderNameHint;
    public $PayerPhoneNumber;
    public $PayerWorkPhoneNumber;
    public $PayerHomePhoneNumber;
    public $PayerAccountInformation;
    public $PurchaseInformation;
    public $RecurrenceType;
    public $ChallengePreference;
    public $TraceId;

    function __construct($requestId = null, $type = PaymentType::Immediate, $guestCheckoutAllowed = true, $allowedFundingSources = array(FundingSourceType::All), $window = "00:30:00", $locale = "hu-HU", $initiateRecurrence = false, $recurrenceId = null, $redirectUrl = null, $callbackUrl = null, $currency = Currency::HUF, $traceId = "")
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
        $this->TraceId = $traceId;
    }

    public function AddTransaction(PaymentTransactionModel $transaction)
    {
        if ($this->Transactions == null) {
            $this->Transactions = array();
        }
        array_push($this->Transactions, $transaction);
    }

    public function AddTransactions($transactions)
    {
        if (!empty($transactions)) {
            foreach ($transactions as $transaction) {
                if ($transaction instanceof PaymentTransactionModel) {
                    $this->AddTransaction($transaction);
                }
            }
        }
    }
}