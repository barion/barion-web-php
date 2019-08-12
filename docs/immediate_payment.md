# Example - immediate payment

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
$ppr->RedirectUrl = "http://webshop.example.com/afterpayment";
$ppr->CallbackUrl = "http://webshop.example.com/processpayment";
$ppr->AddTransaction($trans);
```

At this point, the complete request object looks like this:

```
PreparePaymentRequestModel Object
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