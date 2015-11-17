<?php

class PaymentStateRequestModel extends BaseRequestModel
{
    public $PaymentId;

    function __construct($paymentId)
    {
        $this->PaymentId = $paymentId;
    }
}

?>