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

use Barion\Models\BaseRequestModel;
use Barion\Interfaces\IPaymentTransactionContainer;
use Barion\Enumerations\{
    PaymentType,
    FundingSourceType,
    Currency,
    UILocale
};
use Barion\Enumerations\ThreeDSecure\{
    RecurrenceType,
    ChallengePreference
};

/**
 * Model used to start a new payment on the Barion Smart Gateway.
 */
class PreparePaymentRequestModel extends BaseRequestModel implements IPaymentTransactionContainer
{
    /** 
     * The type of the payment.
     * 
     * @var PaymentType
     */
    public PaymentType $PaymentType;

    /** 
     * Timespan of the period the payment should be reserved for.
     * Applicable if PaymentType = Reservation
     * 
     * @var ?string
     */
    public ?string $ReservationPeriod;

    /** 
     * Timespan of the period the payment should be available for capturing after authorization.
     * Applicable if PaymentType = DelayedCapture
     * 
     * @var ?string
     */
    public ?string $DelayedCapturePeriod;

    /** 
     * Timespan of the period the payment can be completed on the Barion Smart Gateway.
     * 
     * @var string
     */
    public string $PaymentWindow;

    /** 
     * Flag indicating if guest checkout is available for the payment.
     * 
     * @var bool
     */
    public bool $GuestCheckout;
    
    /** 
     * Array of funding source types allowed to complete the payment.
     * 
     * @var array<FundingSourceType> 
     */
    public array $FundingSources;
    
    /** 
     * Internal identifier of the payment, specified by the shop.
     * 
     * @var string
     */
    public string $PaymentRequestId;

    /** 
     * Hint of a payer e-mail address the Barion Smart Gateway should pre-fill for the user.
     * 
     * @var ?string
     */
    public ?string $PayerHint;
    
    /** 
     * Array of payment transactions included in the payment.
     * 
     * @var array<object> 
    */
    public array $Transactions;
    
    /** 
     * Locale of the Barion Smart Gateway for the payment.
     * 
     * @var UILocale
     */
    public UILocale $Locale;

    /** 
     * The order number of the payment, specified by the shop.
     * 
     * @var ?string
     */
    public ?string $OrderNumber;

    /** 
     * Shipping address data connected to a 3D-Secure card payment.
     * 
     * @var ?object
     */
    public ?object $ShippingAddress;

    /** 
     * Billing address data connected to a 3D-Secure card payment.
     * 
     * @var ?object
     */
    public ?object $BillingAddress;

    /** 
     * Flag indicating if this payment is the starting point of a recurring/token payment flow.
     * 
     * @var bool
     */
    public bool $InitiateRecurrence;

    /** 
     * The recurrence identifier (token) of the recurring payment, if applicable.
     * 
     * @var ?string
     */
    public ?string $RecurrenceId;

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
     * The currency of the payment.
     * 
     * @var Currency
     */
    public Currency $Currency;

    /** 
     * Information about the card holder name in case of a 3D-Secure card payment process.
     * 
     * @var ?string
     */
    public ?string $CardHolderNameHint;

    /** 
     * Information about the payer phone number in case of a 3D-Secure card payment process.
     * 
     * @var ?string
     */
    public ?string $PayerPhoneNumber;

    /** 
     * Payer work phone number in case of a 3D-Secure card payment process.
     * 
     * @var ?string
     */
    public ?string $PayerWorkPhoneNumber;

    /** 
     * Payer home phone number in case of a 3D-Secure card payment process.
     * 
     * @var ?string
     */
    public ?string $PayerHomePhoneNumber;

    /** 
     * Payer account information applicable in case of a 3D-Secure card payment process.
     * 
     * @var ?object
     */
    public ?object $PayerAccountInformation;

    /** 
     * Purchase information applicable in case of a 3D-Secure card payment process.
     * 
     * @var ?object
     */
    public ?object $PurchaseInformation;

    /** 
     * Type of recurrence in case of a recurring/token payment scenario.
     * 
     * @var RecurrenceType
     */
    public RecurrenceType $RecurrenceType;

    /** 
     * Challenge preference indicator for a 3D-Secure card payment process.
     * 
     * @var ChallengePreference
     */
    public ChallengePreference $ChallengePreference;

    /** 
     * The trace id of the 3D-Secure payment flow.
     * 
     * @var ?string
     */
    public ?string $TraceId;

    /**
     * Create a new request for starting a payment.
     *
     * @param string $requestId
     * @param PaymentType $paymentType
     * @param bool $guestCheckoutAllowed
     * @param array<FundingSourceType> $allowedFundingSources
     */
    function __construct(string $requestId = "", PaymentType $paymentType = PaymentType::Immediate, bool $guestCheckoutAllowed = true, 
                            array $allowedFundingSources = array(FundingSourceType::All), string $paymentWindow = "00:30:00", UILocale $locale = UILocale::HU, 
                            bool $initiateRecurrence = false, string $recurrenceId = null, string $redirectUrl = null, 
                            string $callbackUrl = null, Currency $currency = Currency::HUF, string $traceId = null)
    {
        $this->PaymentRequestId = $requestId;
        $this->PaymentType = $paymentType;
        $this->PaymentWindow = $paymentWindow;
        $this->GuestCheckout = $guestCheckoutAllowed;
        $this->FundingSources = $allowedFundingSources;
        $this->Locale = $locale;
        $this->InitiateRecurrence = $initiateRecurrence;
        $this->RecurrenceId = $recurrenceId;
        $this->RedirectUrl = $redirectUrl;
        $this->CallbackUrl = $callbackUrl;
        $this->Currency = $currency;
        $this->Transactions = array();
        $this->TraceId = $traceId;
    }
    
    /**
     * Add a single payment transaction to the payment.
     *
     * @param PaymentTransactionModel $transaction
     * @return void
     */
    public function AddTransaction(PaymentTransactionModel $transaction) : void
    {
        $this->Transactions[] = $transaction;
    }

    /** 
     * Add multiple payment transactions to the payment. 
     * 
     * @param array<object> $transactions
     * @return void
    */
    public function AddTransactions(array $transactions) : void
    {
        foreach ($transactions as $transaction) {
            if ($transaction instanceof PaymentTransactionModel) {
                $this->AddTransaction($transaction);
            }
        }
    }
}