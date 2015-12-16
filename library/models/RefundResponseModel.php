<?php

class RefundResponseModel extends BaseResponseModel implements iBarionModel
{
    public $PaymentId;
    public $RefundedTransactions;
    
    function __construct() {
        $this->PaymentId = "";
        $this->RefundedTransactions = array();
    }

    public function fromJson($json)
    {
        if (!empty($json)) {
            parent::fromJson($json);

            $this->PaymentId = jget($json, 'PaymentId');
            $this->RefundedTransactions = array();

            if (!empty($json['RefundedTransactions'])) {
                foreach ($json['RefundedTransactions'] as $key => $value) {
                    $tr = new TransactionDetailModel();
                    $tr->fromJson($value);
                    array_push($this->RefundedTransactions, $tr);
                }
            }
        }
    }
}

?>