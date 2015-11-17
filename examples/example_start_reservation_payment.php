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
$myEmailAddress = "mywebshop@example.com"; // <-- Replace this with your e-mail address in Barion!

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

// create the transaction
$trans = new PaymentTransactionModel();
$trans->POSTransactionId = "TRANS-02";
$trans->Payee = $myEmailAddress;
$trans->Total = 1500;
$trans->Comment = "Test Transaction";
$trans->AddItem($item1); // add the items to the transaction
$trans->AddItem($item2);

// create the request model
$psr = new PreparePaymentRequestModel();
$psr->GuestCheckout = true; // we allow guest checkout
$psr->PaymentType = PaymentType::Reservation; // we want an immediate payment
$psr->ReservationPeriod = "1:00:00:00"; // money is reserved for one day
$psr->PaymentWindow = "00:20:00"; // the payment must be completed in 20 minutes
$psr->FundingSources = array(FundingSourceType::All); // both Barion wallet and bank card accepted
$psr->PaymentRequestId = "TESTPAY-02";
$psr->PayerHint = "user@example.com";
$psr->Locale = Locale::EN; // the UI language will be English 
$psr->OrderNumber = "ORDER-0002";
$psr->ShippingAddress = "12345 NJ, Example ave. 6.";
$psr->AddTransaction($trans);  // add the transaction to the payment

// send the request
$myPayment = $BC->PreparePayment($psr);

if ($myPayment->RequestSuccessful === true) {
  // redirect the user to the Barion Smart Gateway
  header("Location: " . BARION_WEB_URL_TEST . "?id=" . $myPayment->PaymentId);
}

?>