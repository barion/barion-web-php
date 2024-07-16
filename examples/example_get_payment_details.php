<?php

/*
*  Barion PHP library usage example
*  
*  Getting detailed information about a payment
*  
*  © 2024 Barion Payment Inc.
*/

require_once '../library/BarionClient.php';

use Barion\BarionClient;
use Barion\Enumerations\{
    BarionEnvironment
};

$myPosKey = "11111111-1111-1111-1111-111111111111"; // <-- Replace this with your POSKey!
$paymentId = "22222222-2222-2222-2222-222222222222"; // <-- Replace this with the ID of the payment!

// Barion Client that connects to the TEST environment
$BC = new BarionClient(
    poskey: $myPosKey, 
    version: 4, 
    env: BarionEnvironment::Test, 
    useBundledRootCerts: false
);

// send the request
$paymentDetails = $BC->PaymentState($paymentId);

// TODO: process the information contained in $paymentDetails