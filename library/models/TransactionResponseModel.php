<?php

class TransactionResponseModel implements iBarionModel
{
    public $POSTransactionId;
    public $TransactionId;
    public $Status;
    public $TransactionTime;
    public $RelatedId;
    
    function __construct() {
        $this->POSTransactionId = "";
        $this->TransactionId = "";
        $this->Status = "";
        $this->TransactionTime = "";
        $this->RelatedId = "";
    }

    public function fromJson($json)
    {
        if (!empty($json)) {
            $this->POSTransactionId = $json['POSTransactionId'];
            $this->Status = $json['Status'];
            $this->TransactionId = $json['TransactionId'];
            $this->TransactionTime = $json['TransactionTime'];
            $this->RelatedId = $json['RelatedId'];
        }
    }
}

?>