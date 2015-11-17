<?php

class PreparePaymentRequestModel extends BaseRequestModel
{
    public $PaymentType;
    public $ReservationPeriod;
    public $PaymentWindow;
    public $GuestCheckout;
    public $FundingSources;
    public $PaymentRequestId;
    public $PayerHint;
    public $Transactions;
    public $Locale;
    public $OrderNumber;
    public $ShippingAddress;
    public $InitiateRecurrence;
    public $RecurrenceId;
    public $RedirectUrl;
    public $CallbackUrl;

    function __construct($requestId = null, $type = PaymentType::Immediate, $guestCheckoutAllowed = true, $allowedFundingSources = array(FundingSourceType::All), $window = "00:30:00", $locale = "hu-HU", $initiateRecurrence = false, $recurrenceId = null, $redirectUrl = null, $callbackUrl = null)
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
    }

    public function AddTransaction(PaymentTransactionModel $transaction)
    {
        if ($this->Transactions == null) {
            $this->Transactions = array();
        }
        array_push($this->Transactions, $transaction);
    }
    
    public function AddTransactions($transactions){
        if (!empty($transactions)) {
            foreach ($transactions as $transaction) {
              if ($transaction instanceof PaymentTransactionModel) {
                $this->AddTransaction($transaction);
              }
            }
        }
    }
}

?>