# Example - parameters related to 3D Secure authentication

From September 2019 it will be mandatory for online payments to comply with 3D Secure authentication whenever the bank card used for payment is protected by 3D Secure.
The Barion library has been extended with support to special parameters related to 3D Secure authentication.

The purpose of these parameters is to help the Barion API and the webshop cooperate on making the payment process as smooth and frictionless as possible.

### Constructing the payment request

The process is identical to any other simple payment scenario, only the parameter count changed.

There is one or more **Barion\Models\Common\ItemModel** describing the product, as usual:

```php
$item = new Barion\Models\Common\ItemModel();
$item->Name = "TestItem";
$item->Description = "A test item for payment"; 
$item->Quantity = 1;
$item->Unit = "pc";
$item->UnitPrice = 75;
$item->ItemTotal = 75;
$item->SKU = "ITEM-01";
```

These items are then added to a transaction:

```php
$trans = new Barion\Models\Payment\PaymentTransactionModel();
$trans->POSTransactionId = "TRANS-01";
$trans->Payee = $myEmailAddress;
$trans->Total = 75;
$trans->Comment = "Test Transaction";
$trans->AddItem($item);
```

And here come the additional parameters. First, the shipping and billing addresses:

```php
$shippingAddress = new Barion\Models\Secure3d\ShippingAddressModel();
$shippingAddress->Country = "DE";
$shippingAddress->Region = null;
$shippingAddress->City = "Berlin";
$shippingAddress->Zip = "10243";
$shippingAddress->Street = "Karl-Marx-Allee 93A";
$shippingAddress->Street2 = "1. ebene";
$shippingAddress->Street3 = "";
$shippingAddress->FullName = "Thomas Testing";

$billingAddress = new Barion\Models\Secure3d\BillingAddressModel();
$billingAddress->Country = "DE";
$billingAddress->Region = null;
$billingAddress->City = "Berlin";
$billingAddress->Zip = "10243";
$billingAddress->Street = "Karl-Marx-Allee 93A";
$billingAddress->Street2 = "1. ebene";
$billingAddress->Street3 = "";
```

**NOTE:** the older version of the library used the shipping address as one simple string. This method will <u>NO LONGER WORK</u>, the address structure must fully comply with the API documentation. Please review any request assembling in your integration where you are handling a shipping address.

The webshop should supply as much information about the account of the customer as it can. This is done in the **Barion\Models\Secure3d\PayerAccountInformationModel**.

```php
$payerAccountInfo = new Barion\Models\Secure3d\PayerAccountInformationModel();
$payerAccountInfo->AccountId = "4444888888885559";
$payerAccountInfo->AccountCreated = $now;
$payerAccountInfo->AccountCreationIndicator = Barion\Common\Secure3d\AccountCreationIndicator::CreatedDuringThisTransaction;
$payerAccountInfo->AccountLastChanged = $now;
$payerAccountInfo->AccountChangeIndicator = Barion\Common\Secure3d\AccountChangeIndicator::ChangedDuringThisTransaction;
$payerAccountInfo->PasswordLastChanged = $now;
$payerAccountInfo->PasswordChangeIndicator = Barion\Common\Secure3d\PasswordChangeIndicator::NoChange;
$payerAccountInfo->PurchasesInTheLastSixMonths = 6;
$payerAccountInfo->ShippingAddressAdded = $now;
$payerAccountInfo->ShippingAddressUsageIndicator = Barion\Common\Secure3d\ShippingAddressUsageIndicator::ThisTransaction;
$payerAccountInfo->PaymentMethodAdded = $now;
$payerAccountInfo->PaymentMethodIndicator = Barion\Common\Secure3d\PaymentMethodIndicator::ThisTransaction;
$payerAccountInfo->ProvisionAttempts = 1;
$payerAccountInfo->TransactionalActivityPerDay = 1;
$payerAccountInfo->TransactionalActivityPerYear = 100;
$payerAccountInfo->SuspiciousActivityIndicator = Barion\Common\Secure3d\SuspiciousActivityIndicator::NoSuspiciousActivityObserved;
```

Similarly, all known information about the purchase itself shall be supplied in the **PurchaseInformationModel**:

```php
$purchaseInfo = new Barion\Models\Secure3d\PurchaseInformationModel();
$purchaseInfo->DeliveryTimeframe = Barion\Common\Secure3d\DeliveryTimeFrameType::OvernightShipping;
$purchaseInfo->DeliveryEmailAddress = "user@example.com";
$purchaseInfo->PreOrderDate = "2019-08-01";
$purchaseInfo->AvailabilityIndicator = Barion\Common\Secure3d\AvailabilityIndicator::MerchandiseAvailable;
$purchaseInfo->ReOrderIndicator = Barion\Common\Secure3d\ReOrderIndicator::FirstTimeOrdered;
$purchaseInfo->RecurringExpiry = "2099-12-31 23:59:59";
$purchaseInfo->RecurringFrequency = "0";
$purchaseInfo->ShippingAddressIndicator = Barion\Common\Secure3d\ShippingAddressIndicator::ShipToCardholdersBillingAddress;
$purchaseInfo->GiftCardPurchase = null;
$purchaseInfo->PurchaseType = Barion\Common\Secure3d\PurchaseType::GoodsAndServicePurchase;
```

Lastly, the final **Barion\Models\Payment\PreparePaymentRequestModel** can be constructed using the transactions and the extra parameters above, among with known phone numbers and credit card holder name of the customer.

```php
$psr = new Barion\Models\Payment\PreparePaymentRequestModel();
$psr->GuestCheckout = true;
$psr->PaymentType = Barion\Common\PaymentType::Immediate;
$psr->FundingSources = array(Barion\Common\FundingSourceType::All);
$psr->PaymentRequestId = "TESTPAY-01";
$psr->PayerHint = "user@example.com";
$psr->Locale = Barion\Common\UILocale::EN;
$psr->Currency = Barion\Common\Currency::EUR;
$psr->OrderNumber = "ORDER-0001";
$psr->AddTransaction($trans);

$psr->ShippingAddress = $shippingAddress;
$psr->BillingAddress = $billingAddress;
$psr->CardHolderNameHint = "John Doe";
$psr->PayerPhoneNumber = "36301122334";
$psr->PayerWorkPhoneNumber = "36301122334";
$psr->PayerHomePhoneNumber = "36301122334";
$psr->PayerAccountInformation = $payerAccountInfo;
$psr->PurchaseInformation = $purchaseInfo;
```

The complete payment request object looks like this:

```
\Barion\Models\Payment\PreparePaymentRequestModel Object
(
    [PaymentType] => Immediate
    [ReservationPeriod] => 
    [DelayedCapturePeriod] => 
    [PaymentWindow] => 00:30:00
    [GuestCheckout] => 1
    [FundingSources] => Array
        (
            [0] => All
        )

    [PaymentRequestId] => TESTPAY-01
    [PayerHint] => user@example.com
    [Transactions] => Array
        (
            [0] => \Barion\Models\Payment\PaymentTransactionModel Object
                (
                    [POSTransactionId] => TRANS-01
                    [Payee] => barionaccount@demo-merchant.shop
                    [Total] => 75
                    [Comment] => Test Transaction
                    [Items] => Array
                        (
                            [0] => \Barion\Models\Common\ItemModel Object
                                (
                                    [Name] => TestItem
                                    [Description] => A test item for payment
                                    [Quantity] => 1
                                    [Unit] => pc
                                    [UnitPrice] => 75
                                    [ItemTotal] => 75
                                    [SKU] => ITEM-01
                                )

                        )

                    [PayeeTransactions] => Array
                        (
                        )

                )

        )

    [Locale] => en-US
    [OrderNumber] => ORDER-0001
    [ShippingAddress] => \Barion\Models\Secure3d\ShippingAddressModel Object
        (
            [Country] => DE
            [Region] => 
            [City] => Berlin
            [Zip] => 10243
            [Street] => Karl-Marx-Allee 93A
            [Street2] => 1. ebene
            [Street3] => 
            [FullName] => Thomas Testing
        )

    [BillingAddress] => \Barion\Models\Secure3d\BillingAddressModel Object
        (
            [Country] => DE
            [Region] => 
            [City] => Berlin
            [Zip] => 10243
            [Street] => Karl-Marx-Allee 93A
            [Street2] => 1. ebene
            [Street3] => 
        )

    [InitiateRecurrence] => 
    [RecurrenceId] => 
    [RedirectUrl] => 
    [CallbackUrl] => 
    [Currency] => EUR
    [CardHolderNameHint] => John Doe
    [PayerPhoneNumber] => 36301122334
    [PayerWorkPhoneNumber] => 36301122334
    [PayerHomePhoneNumber] => 36301122334
    [PayerAccountInformation] => \Barion\Models\Secure3d\PayerAccountInformationModel Object
        (
            [AccountId] => 4690011905085639
            [AccountCreated] => 
            [AccountCreationIndicator] => CreatedDuringThisTransaction
            [AccountLastChanged] => 
            [AccountChangeIndicator] => ChangedDuringThisTransaction
            [PasswordLastChanged] => 
            [PasswordChangeIndicator] => NoChange
            [PurchasesInTheLastSixMonths] => 6
            [ShippingAddressAdded] => 
            [ShippingAddressUsageIndicator] => ThisTransaction
            [PaymentMethodAdded] => 
            [PaymentMethodIndicator] => ThisTransaction
            [ProvisionAttempts] => 1
            [TransactionalActivityPerDay] => 1
            [TransactionalActivityPerYear] => 100
            [SuspiciousActivityIndicator] => NoSuspiciousActivityObserved
        )

    [PurchaseInformation] => \Barion\Models\Secure3d\PurchaseInformationModel Object
        (
            [DeliveryTimeframe] => OvernightShipping
            [DeliveryEmailAddress] => 
            [PreOrderDate] => 
            [AvailabilityIndicator] => MerchandiseAvailable
            [ReOrderIndicator] => FirstTimeOrdered
            [RecurringExpiry] => 2099-12-31 23:59:59
            [RecurringFrequency] => 0
            [ShippingAddressIndicator] => ShipToCardholdersBillingAddress
            [GiftCardPurchase] => 
            [PurchaseType] => GoodsAndServicePurchase
        )

    [POSKey] => 
)
```

Nothing left than to call the API with the payment request:

```php
$myPayment = $BC->PreparePayment($psr);
```

From here the process is identical to any other payment scenario. The additional 3D Secure related data is used by the Barion server in payment card related communication. Detailed information about the 3D secure authentication result is not disclosed to the webshop.

## Detailed documentation

We strongly suggest that you read this article about parameters related to 3D Secure authentication in the official Barion API documentation:
https://docs.barion.com/Payment-Start-v2-3DS