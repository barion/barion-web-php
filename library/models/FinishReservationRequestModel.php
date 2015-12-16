<?php

class FinishReservationRequestModel extends BaseRequestModel
{
    public $PaymentId;
    public $Transactions;

    function __construct($paymentId)
    {
        $this->PaymentId = $paymentId;
        $this->Transactions = array();
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