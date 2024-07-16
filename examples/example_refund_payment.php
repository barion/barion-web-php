<?php

/*
*  Barion PHP library usage example
*  
*  Refunding a payment partially (refunding 100 Ft from a payment)
*  
*  © 2024 Barion Payment Inc.
*/

require_once '../library/BarionClient.php';

use Barion\BarionClient;
use Barion\Enumerations\{
    BarionEnvironment
};
use Barion\Models\Payment\{
    TransactionToRefundModel
};
use Barion\Models\Refund\{
    RefundRequestModel
};

$myPosKey = "11111111-1111-1111-1111-111111111111"; // <-- Replace this with your POSKey!
$paymentId = "22222222-2222-2222-2222-222222222222"; // <-- Replace this with the ID of the payment!

// Barion Client that connects to the TEST environment
$BC = new BarionClient(
    poskey: $myPosKey, 
    version: 2, 
    env: BarionEnvironment::Test,
    useBundledRootCerts: false
);

// create the refund transaction model
$transactionId = "33333333-3333-3333-3333-333333333333"; // <-- Replace this with the original transaction ID!
$posTransactionId = "TRANS-04"; // <-- Replace this with the original POS transaction ID!
$amountToRefund = 100;
$comment = "Refund because of complaint"; // no more than 640 characters
$trans = new TransactionToRefundModel(
    transactionId: $transactionId,
    posTransactionId: $posTransactionId,
    amountToRefund: $amountToRefund,
    comment: $comment
);

$rr = new RefundRequestModel($paymentId);
$rr->AddTransaction($trans);

$refundResult = $BC->RefundPayment($rr);

if ($refundResult->RequestSuccessful) {
    // TODO: process the information contained in $refundResult
}