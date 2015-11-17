<?php

class TransactionToRefundModel
{
    public $TransactionId;
    public $POSTransactionId;
    public $AmountToRefund;
    public $Comment;

    function __construct($transactionId = null, $posTransactionId = null, $amountToRefund = null, $comment = null)
    {
        $this->TransactionId = $transactionId;
        $this->POSTransactionId = $posTransactionId;
        $this->AmountToRefund = $amountToRefund;
        $this->Comment = $comment;
    }
}

?>