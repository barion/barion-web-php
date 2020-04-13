<?php

/*
*  Barion PHP library usage example
*  
*  Getting detailed information about a payment
*  
*  � 2015 Barion Payment Inc.
*/

$myPosKey = "11111111-1111-1111-1111-111111111111"; // <-- Replace this with your POSKey!
$paymentId = "22222222-2222-2222-2222-222222222222"; // <-- Replace this with the ID of the payment!

// Barion Client that connects to the TEST environment
$BC = new Barion\BarionClient($myPosKey, 2, Barion\Common\BarionEnvironment::Test);

// send the request
$paymentDetails = $BC->GetPaymentState($paymentId);

// TODO: process the information contained in $paymentDetails