<?php

class PaymentTransactionModel
{
    public $POSTransactionId;
    public $Payee;
    public $Total;
    public $Comment;
    public $Items;
    public $PayeeTransactions;

    public function AddItem(ItemModel $item){
        if ($this->Items == null) {
            $this->Items = array();
        }
        array_push($this->Items, $item);
    }
    
    public function AddItems($items){
        if (!empty($items)) {
            foreach ($items as $item) {
              if ($item instanceof ItemModel) {
                $this->AddItem($item);
              }
            }
        }
    }
    
    public function AddPayeeTransaction(PayeeTransactionModel $model) {
        if ($this->PayeeTransactions == null) {
            $this->PayeeTransactions = array();
        }
        array_push($this->PayeeTransactions, $model);
    }
    
    public function AddPayeeTransactions($transactions){
        if (!empty($transactions)) {
            foreach ($transactions as $transaction) {
              if ($transaction instanceof PayeeTransactionModel) {
                $this->AddPayeeTransaction($transaction);
              }
            }
        }
    }
}

?>