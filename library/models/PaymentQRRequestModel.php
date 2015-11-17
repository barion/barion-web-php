<?php

class PaymentQRRequestModel extends BaseRequestModel
{
    public $PaymentId;
    public $Size;
    
    function __construct($paymentId)
    {
        $this->PaymentId = $paymentId;
    }
}

?>