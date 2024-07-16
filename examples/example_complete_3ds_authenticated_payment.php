<?php

/*
*  Barion PHP library usage example
*  
*  Completing a previously 3DSecure-authenticated payment
*  
*  © 2024 Barion Payment Inc.
*/

require_once '../library/BarionClient.php';

use Barion\BarionClient;
use Barion\Enumerations\{
    BarionEnvironment
};
use Barion\Models\Payment\{
    Complete3DSPaymentRequestModel
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

// create the complete payment request model
$completeRequest = new Complete3DSPaymentRequestModel($paymentId);

// call the Barion API
$completeResult = $BC->Complete3DSPayment($completeRequest);

if ($completeResult->RequestSuccessful) {
    // TODO: process the information contained in $completeResult
}