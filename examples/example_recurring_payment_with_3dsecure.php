<?php

/*
*  Barion PHP library usage example
*  
*  Executing a recurring payment of €19.95, using a previously initialized recurrence token, and parameters required for 3D-secure authentication.
*  
*  © 2024 Barion Payment Inc.
*/

require_once '../library/BarionClient.php';

use Barion\BarionClient;
use Barion\Enumerations\{
    BarionEnvironment,
    PaymentType,
    FundingSourceType,
    UILocale,
    Currency
};
use Barion\Enumerations\ThreeDSecure\{
    AccountChangeIndicator,
    AccountCreationIndicator,
    PasswordChangeIndicator,
    ShippingAddressIndicator,
    ShippingAddressUsageIndicator,
    PaymentMethodIndicator,
    SuspiciousActivityIndicator,
    DeliveryTimeFrameType,
    ReOrderIndicator,
    AvailabilityIndicator,
    PurchaseType,
    ChallengePreference
};
use Barion\Models\Common\{
    ItemModel
};
use Barion\Models\ThreeDSecure\{
    ShippingAddressModel,
    BillingAddressModel,
    PayerAccountInformationModel,
    PurchaseInformationModel
};
use Barion\Models\Payment\{
    PaymentTransactionModel,
    PreparePaymentRequestModel
};

$myPosKey = "11111111-1111-1111-1111-111111111111"; // <-- Replace this with your POSKey!
$myEmailAddress = "mywebshop@example.com"; // <-- Replace this with your e-mail address in Barion!

// Barion Client that connects to the TEST environment
$BC = new BarionClient($myPosKey, 2, BarionEnvironment::Test);

// helper variable, containing the timestamp 10 minutes ago
$now = date("Y-m-d H:i:s", (time() - 600));

// e-mail address of the payer
$payerEmail = "john.doe@example.com";

// create the item model
$item = new ItemModel();
$item->Name = "TestItem"; // no more than 250 characters
$item->Description = "A test item for payment"; // no more than 500 characters
$item->Quantity = 1;
$item->Unit = "pc"; // no more than 50 characters
$item->UnitPrice = 19.95;
$item->ItemTotal = 19.95;
$item->SKU = "ITEM-01"; // no more than 100 characters

// create the transaction
$trans = new PaymentTransactionModel();
$trans->POSTransactionId = "TRANS-01";
$trans->Payee = $myEmailAddress; // no more than 256 characters
$trans->Total = 19.95;
$trans->Comment = "Test Transaction"; // no more than 640 characters
$trans->AddItem($item); // add the item to the transaction

// create the addresses
$shippingAddress = new ShippingAddressModel();
$shippingAddress->Country = "DE";
$shippingAddress->Region = null;
$shippingAddress->City = "Berlin";
$shippingAddress->Zip = "10243";
$shippingAddress->Street = "Karl-Marx-Allee 93A";
$shippingAddress->Street2 = "1. ebene";
$shippingAddress->Street3 = "";
$shippingAddress->FullName = "Thomas Testing";

$billingAddress = new BillingAddressModel();
$billingAddress->Country = "DE";
$billingAddress->Region = null;
$billingAddress->City = "Berlin";
$billingAddress->Zip = "10243";
$billingAddress->Street = "Karl-Marx-Allee 93A";
$billingAddress->Street2 = "1. ebene";
$billingAddress->Street3 = "";

// 3DS information about the payer
$payerAccountInfo = new PayerAccountInformationModel();
$payerAccountInfo->AccountId = "4690011905085639";
$payerAccountInfo->AccountCreated = $now;
$payerAccountInfo->AccountCreationIndicator = AccountCreationIndicator::CreatedDuringThisTransaction;
$payerAccountInfo->AccountLastChanged = $now;
$payerAccountInfo->AccountChangeIndicator = AccountChangeIndicator::ChangedDuringThisTransaction;
$payerAccountInfo->PasswordLastChanged = $now;
$payerAccountInfo->PasswordChangeIndicator = PasswordChangeIndicator::NoChange;
$payerAccountInfo->PurchasesInTheLastSixMonths = 6;
$payerAccountInfo->ShippingAddressAdded = $now;
$payerAccountInfo->ShippingAddressUsageIndicator = ShippingAddressUsageIndicator::ThisTransaction;
$payerAccountInfo->PaymentMethodAdded = $now;
$payerAccountInfo->PaymentMethodIndicator = PaymentMethodIndicator::ThisTransaction;
$payerAccountInfo->ProvisionAttempts = 1;
$payerAccountInfo->TransactionalActivityPerDay = 1;
$payerAccountInfo->TransactionalActivityPerYear = 100;
$payerAccountInfo->SuspiciousActivityIndicator = SuspiciousActivityIndicator::NoSuspiciousActivityObserved;

// 3DS information about the purchase
$purchaseInfo = new PurchaseInformationModel();
$purchaseInfo->DeliveryTimeframe = DeliveryTimeFrameType::OvernightShipping;
$purchaseInfo->DeliveryEmailAddress = $payerEmail;
$purchaseInfo->PreOrderDate = $now;
$purchaseInfo->AvailabilityIndicator = AvailabilityIndicator::MerchandiseAvailable;
$purchaseInfo->ReOrderIndicator = ReOrderIndicator::FirstTimeOrdered;
$purchaseInfo->RecurringExpiry = "2099-12-31 23:59:59";
$purchaseInfo->RecurringFrequency = "0";
$purchaseInfo->ShippingAddressIndicator = ShippingAddressIndicator::ShipToCardholdersBillingAddress;
$purchaseInfo->GiftCardPurchase = null;
$purchaseInfo->PurchaseType = PurchaseType::GoodsAndServicePurchase;

// create the request model
$psr = new PreparePaymentRequestModel();
$psr->GuestCheckout = true; // we allow guest checkout
$psr->PaymentType = PaymentType::Immediate; // we want an immediate payment
$psr->FundingSources = array(FundingSourceType::All); // both Barion wallet and bank card accepted
$psr->PaymentRequestId = "TESTPAY-01"; // no more than 100 characters
$psr->PayerHint = $payerEmail; // no more than 256 characters
$psr->Locale = UILocale::EN; // the UI language will be English 
$psr->Currency = Currency::EUR;
$psr->OrderNumber = "ORDER-0001"; // no more than 100 characters
$psr->AddTransaction($trans); // add the transaction to the payment

// adding the 3d secure compliant parameters to the request
$psr->ShippingAddress = $shippingAddress;
$psr->BillingAddress = $billingAddress;
$psr->CardHolderNameHint = "John Doe";
$psr->PayerPhoneNumber = "36301122334";
$psr->PayerWorkPhoneNumber = "36301122334";
$psr->PayerHomePhoneNumber = "36301122334";
$psr->PayerAccountInformation = $payerAccountInfo;
$psr->PurchaseInformation = $purchaseInfo;
$psr->ChallengePreference = ChallengePreference::NoPreference;

// setting the properties related to token/recurring payment
$psr->InitiateRecurrence = false; // InitiateRecurrence is false, because the webshop already has a token initialized
$psr->RecurrenceId = "XXXXXXXX"; // replace this with the previously initialized token for this recurrence
$psr->RecurrenceType = "RecurringPayment"; // RecurrenceType indicates that this is a recurring payment charge (for merchant-initiated, or simple token payments, use 'MerchantInitiatedPayment' instead)
$psr->TraceId = "XXXXXXXX"; // replace this with the corresponding TraceId received when initializing the token payment!

// send the request
$paymentResponse = $BC->PreparePayment($psr);

if ($paymentResponse->RequestSuccessful === true) {
  // NOTE: since this is a recurring payment execution, no redirect is taking place - the charge happens immediately
  // TODO: process the response found in $paymentResponse
}