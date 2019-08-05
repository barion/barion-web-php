# BarionPHP

**BarionPHP** is a compact PHP library to manage online e-money and card payments via the *Barion Smart Gateway*.
It allows you to accept credit card and e-money payments in just a few lines of code.

**BarionPHP** lets you
* Start an online immediate or reservation payment easily
* Get details about a given payment
* Finish an ongoing reservation payment completely or partially, with automatic refund support
* Refund a completed payment transaction completely or partially

All with just a few simple pieces of code!

# Version history
* **1.3.2** August 05. 2019.
* **1.3.1** March 20. 2019.
* **1.3.0** March 12. 2019.
* **1.2.9** May 16. 2017.
* **1.2.8** April 13. 2017.
* **1.2.7** February 14.  2017.
* **1.2.5** November 07.  2016.
* **1.2.4** May 25.  2016.
* **1.2.3** January 14.  2016.
* **1.2.2** January 11.  2016.
* **1.1.0** November 27. 2015.
* **1.0.1** November 26. 2015.
* **1.0.0** November 17. 2015.

For details about version changes, please refer to the **changelog.txt** file.

# System requirements

* PHP 5.6 or higher
* cURL module enabled (at least v7.18.1)
* SSL enabled (systems using OpenSSL with the version of 0.9.8f at least)

# Installation

Copy the contents of the **barion** library into the desired folder. Be sure to have access to the path when running your PHP script.

# Basic usage

Include the **BarionClient** class in your PHP script:

```php
require_once 'library/BarionClient.php';
```

Then instantiate a Barion client. To achieve this, you must supply three parameters.

First, the secret key of the online store registered in Barion (called POSKey)

```php
$myPosKey = "9c165cfc-cbd1-452f-8307-21a3a9cee664";
```

The API version number (2 by default)

```php
$apiVersion = 2;
```

The environment to connect to. This can be the test system, or the production environment.

```php
// Test environment
$environment = BarionEnvironment::Test;

// Production environment
$environment = BarionEnvironment::Prod;
```

With these parameters you can create an instance of the **BarionClient** class:

```php
$BC = new BarionClient($myPosKey, $apiVersion, $environment);
```

If you're having problems with the SSL connection then you can set the fourth parameter to true: `$useBundledRootCerts`
This will use the bundled root certificate list instead of the server provided one. 

# Examples

## Starting an online payment

Let's see a basic example of using the Barion library. We are going to start an immediate online payment, where the user can pay for one product which costs 1 000 HUF.

### 1. Creating the request object

To start an online payment, you have to create one or more **Payment Transaction** objects, add transaction **Items** to them, then group these transactions together in a **Payment** object.

First, create an **ItemModel**:

```php
$item = new ItemModel();
$item->Name = "TestItem";
$item->Description = "A test product";
$item->Quantity = 1;
$item->Unit = "piece";
$item->UnitPrice = 1000;
$item->ItemTotal = 1000;
$item->SKU = "ITEM-01";
```

Then create a **PaymentTransactionModel** and add the **Item** mentioned above to it:

```php
$trans = new PaymentTransactionModel();
$trans->POSTransactionId = "TRANS-01";
$trans->Payee = "webshop@example.com";
$trans->Total = 1000;
$trans->Currency = Currency::HUF;
$trans->Comment = "Test transaction containing the product";
$trans->AddItem($item);
```

Finally, create a **PreparePaymentRequestModel** and add the **PaymentTransactionModel** mentioned above to it:

```php
$ppr = new PreparePaymentRequestModel();
$ppr->GuestCheckout = true;
$ppr->PaymentType = PaymentType::Immediate;
$ppr->FundingSources = array(FundingSourceType::All);
$ppr->PaymentRequestId = "PAYMENT-01";
$ppr->PayerHint = "user@example.com";
$ppr->Locale = UILocale::EN;
$ppr->OrderNumber = "ORDER-0001";
$ppr->Currency = Currency::HUF;
$ppr->ShippingAddress = "12345 NJ, Example ave. 6.";
$ppr->RedirectUrl = "http://webshop.example.com/afterpayment";
$ppr->CallbackUrl = "http://webshop.example.com/processpayment";
$ppr->AddTransaction($trans);
```

At this point, the complete request object looks like this:

```
PreparePaymentRequestModel Object
(
    [PaymentType] => Immediate
    [ReservationPeriod =>
    [PaymentWindow] => 00:30:00
    [GuestCheckout] => 1
    [FundingSources] => Array
        (
            [0] => All
        )
    [PaymentRequestId] => PAYMENT-01
    [PayerHint] => user@example.com
    [Transactions] => Array
        (
            [0] => PaymentTransactionModel Object
                (
                    [POSTransactionId] => TRANS-01
                    [Payee] => webshop@example.com
                    [Total] => 1000
                    [Currency] => HUF
                    [Comment] => Test transaction containing the product
                    [Items] => Array
                        (
                            [0] => ItemModel Object
                                (
                                    [Name] => TestItem
                                    [Description] => A test product
                                    [Quantity] => 1
                                    [Unit] => piece
                                    [UnitPrice] => 1000
                                    [ItemTotal] => 1000
                                    [SKU] => ITEM-01
                                )
                        )
                    [PayeeTransactions] =>
                )
        )
    [Locale] => en-US
    [Currency] => HUF
    [OrderNumber] => ORDER-0001
    [ShippingAddress] => 12345 NJ, Example ave. 6.
    [InitiateRecurrence] =>
    [RecurrenceId] =>
    [RedirectUrl] => http://webshop.example.com/afterpayment
    [CallbackUrl] => http://webshop.example.com/processpayments
    [POSKey] =>
)
```
**Note:** the secret *POSKey* used for authentication is not part of the request object.
The Barion client class automatically injects this value into every request sent to the Barion API.

### 2. Calling the Barion API

Now you can call the **PreparePayment** method of the Barion client with the request model you just created:

```php
$myPayment = $BC->PreparePayment($ppr);
```

The Barion API now prepares a payment entity that can be paid by anyone.

The **$myPayment** variable holds the response received from the Barion API, which is an instance of a **PreparePaymentResponseModel** object. It should look something like this:

```
PreparePaymentResponseModel Object
(
    [PaymentId] => 64157032-d3dc-4296-aeda-fd4b0994c64e
    [PaymentRequestId] => PAYMENT-01
    [Status] => Prepared
    [Transactions] => Array
        (
            [0] => TransactionResponseModel Object
                (
                    [POSTransactionId] => TRANS-01
                    [TransactionId] => fb06f46e-7a55-4da5-9a62-992089b3dd23
                    [Status] => Prepared
                    [TransactionTime] => 2015-11-12T09:24:14.074
                    [RelatedId] =>
                )
            [1] => TransactionResponseModel Object
                (
                    [POSTransactionId] =>
                    [TransactionId] => 49a9c395-833a-445f-82dd-b1d12784b3ef
                    [Status] => Prepared
                    [TransactionTime] => 2015-11-12T09:24:14.262
                    [RelatedId] =>
                )
            [2] => TransactionResponseModel Object
                (
                    [POSTransactionId] =>
                    [TransactionId] => c91a0006-4b6b-41ed-bdbd-5cfb3d67528b
                    [Status] => Prepared
                    [TransactionTime] => 2015-11-12T09:24:14.262
                    [RelatedId] =>
                )
        )
    [QRUrl] => https://api.barion.com/qr/generate?paymentId=64157032-d3dc-4296-aeda-fd4b0994c64e&size=Large
    [RecurrenceResult] => None
    [PaymentRedirectUrl] => https://secure.barion.com/Pay?id=64157032-d3dc-4296-aeda-fd4b0994c64e
    [Errors] => Array
        (
        )
    [RequestSuccessful] => 1
)
```
The **RequestSuccessful** parameter shows that the request was successfully sent, and the response was successfully received. **PaymentId** holds the public identifier of the payment you just created in the Barion system, and the **Transactions** array contains the transactions related to this payment. The fist element is the transaction you constructed before, and the other two are fee transactions generated by the Barion Smart Gateway. In this case, these are gateway usage and bank card processing fees.

**Note:** The amount and types of the related fee transactions depend on the request caller identity - please refer to the documentation at https://docs.barion.com or contact our Sales Department.

**Note:** The **RequestSuccessful** parameter only indicates that the HTTP request-response communication itself was successfully done. It does NOT refer to any part of the payment process. If anything failed during the processing of the request, API error messages are found in the **Errors** array.

### 3. Redirecting the user to the Barion Smart Gateway

You can use the **PaymentId** value in the response to redirect the user to the Barion Smart Gateway. You have to supply this identifier in the **Id** query string parameter.
The complete redirect URL looks like this:

```
https://secure.barion.com/Pay?id=64157032-d3dc-4296-aeda-fd4b0994c64e
```

The user can now complete the payment at the Barion Smart Gateway.

## Getting information about a payment

In this example we are going to get detailed information about the payment we just created above.

### 1. Creating the request object

To request details about a payment, you only need one parameter: the payment identifier. This is the **PaymentId** we have used earlier to redirect the user.

```
64157032-d3dc-4296-aeda-fd4b0994c64e
```

### 2. Calling the Barion API

To request payment details, we call the **GetPaymentState** method of the Barion client class with the identifier above:

```php
$paymentDetails = $BC->GetPaymentState("64157032-d3dc-4296-aeda-fd4b0994c64e");
```

The **$paymentDetails** variable holds the response received from the Barion API, which is an instance of a **PaymentStateResponseModel** object. It should look something like this:

```
PaymentStateResponseModel Object
(
    [PaymentId] => 64157032-d3dc-4296-aeda-fd4b0994c64e
    [PaymentRequestId] => PAYMENT-01
    [POSId] =>
    [POSName] => ExampleWebshop
    [Status] => Prepared
    [PaymentType] => Immediate
    [FundingSource] =>
    [AllowedFundingSources] => Array
        (
            [0] => All
        )
    [GuestCheckout] => 1
    [CreatedAt] => 2015-11-12T09:47:12.173
    [ValidUntil] => 2015-11-12T10:17:12.173
    [CompletedAt] =>
    [ReservedUntil] =>
    [Total] => 1000
    [Currency] => HUF
    [Transactions] => Array
        (
            [0] => TransactionDetailModel Object
                (
                    [TransactionId] => fb06f46e-7a55-4da5-9a62-992089b3dd23
                    [POSTransactionId] => TRANS-01
                    [TransactionTime] => 2015-11-12T09:47:12.189
                    [Total] => 1000
                    [Currency] => HUF
                    [Payer] => UserModel Object
                        (
                            [Name] =>
                            [Email] =>
                        )
                    [Payee] => UserModel Object
                        (
                            [Name] => Example Webshop Technologies Ltd.
                            [Email] => webshop@example.com
                        )
                    [Comment] => Test transaction containing the product
                    [Status] => Prepared
                    [TransactionType] => Unspecified
                    [Items] => Array
                        (
                            [0] => ItemModel Object
                                (
                                    [Name] => TestItem
                                    [Description] => A test product
                                    [Quantity] => 1
                                    [Unit] => piece
                                    [UnitPrice] => 1000
                                    [ItemTotal] => 1000
                                    [SKU] => ITEM-01
                                )
                        )
                    [RelatedId] =>
                    [POSId] => 04ed8c89-c9bd-4c17-92f6-a0964587bbff
                    [PaymentId] => 64157032-d3dc-4296-aeda-fd4b0994c64e
                )
            [1] => TransactionDetailModel Object
                (
                    [TransactionId] => 49a9c395-833a-445f-82dd-b1d12784b3ef
                    [POSTransactionId] =>
                    [TransactionTime] => 2015-11-12T09:47:12.205
                    [Total] => 50
                    [Currency] => HUF
                    [Payer] => UserModel Object
                        (
                            [Name] => Example Webshop Technologies Ltd.
                            [Email] => webshop@example.com
                        )
                    [Payee] => UserModel Object
                        (
                            [Name] =>
                            [Email] =>
                        )
                    [Comment] =>
                    [Status] => Prepared
                    [TransactionType] => GatewayFee
                    [Items] => Array
                        (
                        )
                    [RelatedId] =>
                    [POSId] => 04ed8c89-c9bd-4c17-92f6-a0964587bbff
                    [PaymentId] => 64157032-d3dc-4296-aeda-fd4b0994c64e
                )
            [2] => TransactionDetailModel Object
                (
                    [TransactionId] => c91a0006-4b6b-41ed-bdbd-5cfb3d67528b
                    [POSTransactionId] =>
                    [TransactionTime] => 2015-11-12T09:47:12.205
                    [Total] => 10
                    [Currency] => HUF
                    [Payer] => UserModel Object
                        (
                            [Name] => Example Webshop Technologies Ltd.
                            [Email] => webshop@example.com
                        )
                    [Payee] => UserModel Object
                        (
                            [Name] =>
                            [Email] =>
                        )
                    [Comment] =>
                    [Status] => Prepared
                    [TransactionType] => CardProcessingFee
                    [Items] => Array
                        (
                        )
                    [RelatedId] =>
                    [POSId] => 04ed8c89-c9bd-4c17-92f6-a0964587bbff
                    [PaymentId] => 64157032-d3dc-4296-aeda-fd4b0994c64e
                )
        )
    [RecurrenceResult] =>
    [Errors] => Array
        (
        )
    [RequestSuccessful] => 1
)
```

The payment is in **Prepared** status, which means it is still waiting to be paid.
If we wait until the user completes the payment, and send the request again, we should get a slightly different result with more information:

```
PaymentStateResponseModel Object
(
    [PaymentId] => 64157032-d3dc-4296-aeda-fd4b0994c64e
    [PaymentRequestId] => PAYMENT-01
    [POSId] =>
    [POSName] => ExampleWebshop
    [Status] => Succeeded
    [PaymentType] => Immediate
    [FundingSource] => BankCard
    [FundingInformation] => FundingInformationModel Object
        (
            [BankCard] => BankCardModel Object
                (
                    [MaskedPan] => 1234
                    [BankCardType] => MasterCard
                    [ValidThruYear] => 2019
                    [ValidThruMonth] => 9
                )

            [AuthorizationCode] => 123456
        )
    [AllowedFundingSources] => Array
        (
            [0] => All
        )
    [GuestCheckout] => 1
    [CreatedAt] => 2015-11-12T09:47:12.173
    [ValidUntil] => 2015-11-12T10:17:12.173
    [CompletedAt] => 2015-11-12T10:09:35.525
    [ReservedUntil] =>
    [Total] => 1000
    [Currency] => HUF
    [Transactions] => Array
        (
            [0] => TransactionDetailModel Object
                (
                    [TransactionId] => fb06f46e-7a55-4da5-9a62-992089b3dd23
                    [POSTransactionId] => TRANS-01
                    [TransactionTime] => 2015-11-12T09:47:12.189
                    [Total] => 1000
                    [Currency] => HUF
                    [Payer] => UserModel Object
                        (
                            [Name] => John Doe
                            [Email] => user@example.com
                        )
                    [Payee] => UserModel Object
                        (
                            [Name] => Example Webshop Technologies Ltd.
                            [Email] => webshop@example.com
                        )
                    [Comment] => Test transaction containing the product
                    [Status] => Succeeded
                    [TransactionType] => CardPayment
                    [Items] => Array
                        (
                            [0] => ItemModel Object
                                (
                                    [Name] => TestItem
                                    [Description] => A test product
                                    [Quantity] => 1
                                    [Unit] => piece
                                    [UnitPrice] => 1000
                                    [ItemTotal] => 1000
                                    [SKU] => ITEM-01
                                )
                        )
                    [RelatedId] =>
                    [POSId] => 04ed8c89-c9bd-4c17-92f6-a0964587bbff
                    [PaymentId] => 64157032-d3dc-4296-aeda-fd4b0994c64e
                )
            [1] => TransactionDetailModel Object
                (
                    [TransactionId] => 49a9c395-833a-445f-82dd-b1d12784b3ef
                    [POSTransactionId] =>
                    [TransactionTime] => 2015-11-12T09:47:12.205
                    [Total] => 50
                    [Currency] => HUF
                    [Payer] => UserModel Object
                        (
                            [Name] => Example Webshop Technologies Ltd.
                            [Email] => webshop@example.com
                        )
                    [Payee] => UserModel Object
                        (
                            [Name] =>
                            [Email] =>
                        )
                    [Comment] =>
                    [Status] => Succeeded
                    [TransactionType] => GatewayFee
                    [Items] => Array
                        (
                        )
                    [RelatedId] =>
                    [POSId] => 04ed8c89-c9bd-4c17-92f6-a0964587bbff
                    [PaymentId] => 64157032-d3dc-4296-aeda-fd4b0994c64e
                )
            [2] => TransactionDetailModel Object
                (
                    [TransactionId] => c91a0006-4b6b-41ed-bdbd-5cfb3d67528b
                    [POSTransactionId] =>
                    [TransactionTime] => 2015-11-12T09:47:12.205
                    [Total] => 10
                    [Currency] => HUF
                    [Payer] => UserModel Object
                        (
                            [Name] => Example Webshop Technologies Ltd.
                            [Email] => webshop@example.com
                        )
                    [Payee] => UserModel Object
                        (
                            [Name] =>
                            [Email] =>
                        )
                    [Comment] =>
                    [Status] => Succeeded
                    [TransactionType] => CardProcessingFee
                    [Items] => Array
                        (
                        )
                    [RelatedId] =>
                    [POSId] => 04ed8c89-c9bd-4c17-92f6-a0964587bbff
                    [PaymentId] => 64157032-d3dc-4296-aeda-fd4b0994c64e
                )
        )
    [RecurrenceResult] =>
    [Errors] => Array
        (
        )
    [RequestSuccessful] => 1
)
```

As you can see, the payment status is now **Succeeded**, which means the payment has been completed successfully. The **FundingSource** parameter shows that the payment was completed using a bank card. Information about the bank card is available in the **FundingInformation** property. Also, the **Payer** parameter of the first transaction shows that the payment was completed by the *John Doe (user@example.com)* user account.

# Basic troubleshooting

Here are a few common mistakes you might want to double check for before reaching out to our support:

**1. I get a "User authentication failed" error when sending my request**
- Check if you are sending the correct POSkey to the correct environment, e.g. if you want to call the API in the TEST environment, use the POSkey of the shop that you registered on the TEST website.
- Check if the sent data is actually a valid JSON string, without any special characters, delimiters, line-breaks or invalid encoding.

**2. I get a "Shop is closed" error message in the TEST environment**
- Check if your shop is open after logging in to the Barion Test website. Please note that you must fill out every data of your shop and then send it to approval. After this, approval will automatically be completed and your shop will be in Open status. This only applies to the TEST environment.


# Further examples

To view more examples about the usage of the Barion library, refer to the example files found in the examples folder of the repository.

*© 2017 Barion Payment Inc.*
