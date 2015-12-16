<?php

class PayeeTransactionModel
{
    public $POSTransactionId;
    public $Payee;
    public $Total;
    public $Comment;
    
    function __construct() {
        $this->POSTransactionId = "";
        $this->Payee = "";
        $this->Total = 0;
        $this->Comment = "";
    }
}

?>