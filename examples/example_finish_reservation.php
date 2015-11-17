<?php

/*
*  Barion PHP library usage example
*  
*  Starting a reservation payment with two products
*  
*  © 2015 Barion Payment Inc.
*/

require_once '../library/BarionClient.php';

$myPosKey = "11111111-1111-1111-1111-111111111111"; // <-- Replace this with your POSKey!
$paymentId = "22222222-2222-2222-2222-222222222222"; // <-- Replace this with the ID of the payment!

// Barion Client that connects to the TEST environment
$BC = new BarionClient($myPosKey, 2, BarionEnvironment::Test);

// create the item models
$item1 = new ItemModel();
$item1->Name = "TestItem";
$item1->Description = "A test item for payment";
$item1->Quantity = 1;
$item1->Unit = "piece";
$item1->UnitPrice = 1000;
$item1->ItemTotal = 1000;
$item1->SKU = "ITEM-01";

$item2 = new ItemModel();
$item2->Name = "AnotherTestItem";
$item2->Description = "Another test item for payment";
$item2->Quantity = 2;
$item2->Unit = "piece";
$item2->UnitPrice = 250;
$item2->ItemTotal = 250;
$item2->SKU = "ITEM-02";

// create the transaction model
$trans = new PaymentTransactionModel();
$trans->TransactionId = "33333333-3333-3333-3333-333333333333"; // <-- Replace this with the original transaction ID!
$trans->Total = 1500;
$trans->Comment = "Reservation complete";
$trans->AddItem($item1); // add the items to the transaction
$trans->AddItem($item2);

// create the request object
$frrm = new FinishReservationRequestModel($paymentId);
$frrm->AddTransaction($trans); // add the transaction to the request

// send the request
$finishReservationResult = $BC->FinishReservation($frrm);

if ($finishReservationResult->RequestSuccessful) {
    // TODO: process the information contained in $finishReservationResult
}

?>