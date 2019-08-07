<?php

/*
*  Barion PHP library usage example
*  
*  Capturing a previously authorized amount in a Delayed Capture payment scenario
*  
*  � 2019 Barion Payment Inc.
*/

require_once '../library/BarionClient.php';

$myPosKey = "11111111-1111-1111-1111-111111111111"; // <-- Replace this with your POSKey!
$paymentId = "22222222-2222-2222-2222-222222222222"; // <-- Replace this with the ID of the payment!

// Barion Client that connects to the TEST environment
$BC = new BarionClient($myPosKey, 2, BarionEnvironment::Test);

// create the item model
$item = new ItemModel();
$item->Name = "TestItem"; // no more than 250 characters
$item->Description = "A test item for delayed capture payment"; // no more than 500 characters
$item->Quantity = 1;
$item->Unit = "piece"; // no more than 50 characters
$item->UnitPrice = 1000;
$item->ItemTotal = 1000;
$item->SKU = "ITEM-01"; // no more than 100 characters

// create the transaction model
$trans = new TransactionToFinishModel();
$trans->TransactionId = "33333333-3333-3333-3333-333333333333"; // <-- Replace this with the original transaction ID!
$trans->Total = 1000;
$trans->Comment = "Transaction completed"; // no more than 640 characters
$trans->AddItem($item);

// create the request object
$crm = new CaptureRequestModel($paymentId);
$crm->AddTransaction($trans); // add the transaction to the request

// send the request
$captureResult = $BC->Capture($crm);

if ($captureResult->RequestSuccessful) {
    // TODO: process the information contained in $finishReservationResult
}