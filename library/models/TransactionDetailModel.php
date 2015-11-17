<?php

class TransactionDetailModel implements iBarionModel
{
    public $TransactionId;
    public $POSTransactionId;
    public $TransactionTime;
    public $Total;
    public $Payer;
    public $Payee;
    public $Comment;
    public $Status;
    public $TransactionType;
    public $Items;
    public $RelatedId;
    public $POSId;
    public $PaymentId;

    public function fromJson($json)
    {
        if (!empty($json)) {
            $this->TransactionId = $json['TransactionId'];
            $this->POSTransactionId = $json['POSTransactionId'];
            $this->TransactionTime = $json['TransactionTime'];
            $this->Total = $json['Total'];

            $this->Payer = new UserModel();
            $this->Payer->fromJson($json['Payer']);

            $this->Payee = new UserModel();
            $this->Payee->fromJson($json['Payee']);

            $this->Comment = $json['Comment'];
            $this->Status = $json['Status'];
            $this->TransactionType = $json['TransactionType'];

            $this->Items = array();
            
            if (!empty($json['Items'])) {
              foreach ($json['Items'] as $i) {
                $item = new ItemModel();
                $item->fromJson($i);
                array_push($this->Items, $item);
              }
            }

            $this->RelatedId = $json['RelatedId'];
            $this->POSId = $json['POSId'];
            $this->PaymentId = $json['PaymentId'];
        }
    }
}

?>