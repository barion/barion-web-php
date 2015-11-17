<?php

/*
*  Barion PHP library usage example
*  
*  Starting an immediate payment with one product
*  
*  © 2015 Barion Payment Inc.
*/

require_once '../library/BarionClient.php';

$myPosKey = "11111111-1111-1111-1111-111111111111"; // <-- Replace this with your POSKey!
$myEmailAddress = "mywebshop@example.com"; // <-- Replace this with your e-mail address in Barion!

// Barion Client that connects to the TEST environment
$BC = new BarionClient($myPosKey, 2, BarionEnvironment::Test);

// create the item model
$item = new ItemModel();
$item->Name = "TestItem";
$item->Description = "A test item for payment";
$item->Quantity = 1;
$item->Unit = "piece";
$item->UnitPrice = 1000;
$item->ItemTotal = 1000;
$item->SKU = "ITEM-01";

// create the transaction
$trans = new PaymentTransactionModel();
$trans->POSTransactionId = "TRANS-01";
$trans->Payee = $myEmailAddress;
$trans->Total = 1000;
$trans->Comment = "Test Transaction";
$trans->AddItem($item); // add the item to the transaction

// create the request model
$psr = new PreparePaymentRequestModel();
$psr->GuestCheckout = true; // we allow guest checkout
$psr->PaymentType = PaymentType::Immediate; // we want an immediate payment
$psr->FundingSources = array(FundingSourceType::All); // both Barion wallet and bank card accepted
$psr->PaymentRequestId = "TESTPAY-01";
$psr->PayerHint = "user@example.com";
$psr->Locale = Locale::EN; // the UI language will be English 
$psr->OrderNumber = "ORDER-0001";
$psr->ShippingAddress = "12345 NJ, Example ave. 6.";
$psr->AddTransaction($trans); // add the transaction to the payment

// send the request
$myPayment = $BC->PreparePayment($psr);

if ($myPayment->RequestSuccessful === true) {
  // redirect the user to the Barion Smart Gateway
  header("Location: " . BARION_WEB_URL_TEST . "?id=" . $myPayment->PaymentId);
}

?>