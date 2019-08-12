# Example - reservation payment

Here we are going to start a reservation payment, where the user pays for two products, but in the end, the shop can only ship one of them.

### 1. Creating the request object

To start an online payment, you have to create one or more **Payment Transaction** objects, add transaction **Items** to them, then group these transactions together in a **Payment** object.

First, create the two **ItemModel** instances:

```php
$item1 = new ItemModel();
$item1->Name = "BestPresso Coffee Machine";
$item1->Description = "BPC2303 coffee machine, 1-year warranty";
$item1->Quantity = 1;
$item1->Unit = "piece";
$item1->UnitPrice = 499.95;
$item1->ItemTotal = 499.95;
$item1->SKU = "BPC2303";

$item2 = new ItemModel();
$item2->Name = "BestPresso XL Coffee Machine";
$item2->Description = "BPC4000 XL professional coffee machine, 3-year warranty";
$item2->Quantity = 1;
$item2->Unit = "piece";
$item2->UnitPrice = 1199.95;
$item2->ItemTotal = 1199.95;
$item2->SKU = "BPC4000";
```

Then create a **PaymentTransactionModel** and add the two **ItemModel** instances mentioned above to it:

```php
$trans = new PaymentTransactionModel();
$trans->POSTransactionId = "TRANS-01";
$trans->Payee = "barionaccount@demo-merchant.shop";
$trans->Total = 1699.9;
$trans->Currency = Currency::EUR;
$trans->Comment = "Test transaction containing the products";
$trans->AddItem($item1);
$trans->AddItem($item2);
```

Finally, create a **PreparePaymentRequestModel** and add the **PaymentTransactionModel** mentioned above to it:

```php
$ppr = new PreparePaymentRequestModel();
$ppr->GuestCheckout = true;
$ppr->PaymentType = PaymentType::Reservation;
$ppr->ReservationPeriod = "7.00:00:00";
$ppr->FundingSources = array(FundingSourceType::All);
$ppr->PaymentRequestId = "PAYMENT-01";
$ppr->PayerHint = "user@example.com";
$ppr->Locale = UILocale::EN;
$ppr->OrderNumber = "ORDER-0001";
$ppr->Currency = Currency::EUR;
$ppr->RedirectUrl = "http://webshop.example.com/afterpayment";
$ppr->CallbackUrl = "http://webshop.example.com/processpayment";
$ppr->AddTransaction($trans);
```

At this point, the complete request object looks like this:

```
PreparePaymentRequestModel Object
(
    [PaymentType] => Reservation
    [ReservationPeriod] => 7.00:00:00
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
                    [Payee] => barionaccount@demo-merchant.shop
                    [Total] => 1699.9
                    [Comment] => Test transaction containing the products
                    [Items] => Array
                        (
                            [0] => ItemModel Object
                                (
                                    [Name] => BestPresso Coffee Machine
                                    [Description] => BPC2303 coffee machine, 1-year warranty
                                    [Quantity] => 1
                                    [Unit] => piece
                                    [UnitPrice] => 499.95
                                    [ItemTotal] => 499.95
                                    [SKU] => BPC2303
                                )

                            [1] => ItemModel Object
                                (
                                    [Name] => BestPresso XL Coffee Machine
                                    [Description] => BPC4000 XL professional coffee machine, 3-year warranty
                                    [Quantity] => 1
                                    [Unit] => piece
                                    [UnitPrice] => 1199.95
                                    [ItemTotal] => 1199.95
                                    [SKU] => BPC4000
                                )

                        )

                    [PayeeTransactions] => Array
                        (
                        )

                    [Currency] => EUR
                )

        )

    [Locale] => en-US
    [OrderNumber] => ORDER-0001
    [ShippingAddress] => 
    [BillingAddress] => 
    [InitiateRecurrence] => 
    [RecurrenceId] => 
    [RedirectUrl] => http://webshop.example.com/afterpayment
    [CallbackUrl] => http://webshop.example.com/processpayment
    [Currency] => EUR
    [CardHolderNameHint] => 
    [PayerPhoneNumber] => 
    [PayerWorkPhoneNumber] => 
    [PayerHomePhoneNumber] => 
    [PayerAccountInformation] => 
    [PurchaseInformation] => 
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
    [PaymentId] => d1d1c3eebab04c56b3c3b30c70f2d278
    [PaymentRequestId] => PAYMENT-01
    [Status] => Prepared
    [Transactions] => Array
        (
            [0] => TransactionResponseModel Object
                (
                    [POSTransactionId] => TRANS-01
                    [TransactionId] => 2831dcca202948e1954b91b90a24ca9c
                    [Status] => Prepared
                    [TransactionTime] => 2019-08-12T04:38:57.739
                    [RelatedId] => 
                )

            [1] => TransactionResponseModel Object
                (
                    [POSTransactionId] => 
                    [TransactionId] => 811a6034dadf452db5233b8138b6dd58
                    [Status] => Reserved
                    [TransactionTime] => 2019-08-12T04:38:57.759
                    [RelatedId] => 
                )

        )

    [QRUrl] => https://api.barion.com/qr/generate?paymentId=d1d1c3ee-bab0-4c56-b3c3-b30c70f2d278&size=Large
    [RecurrenceResult] => None
    [PaymentRedirectUrl] => https://secure.barion.com/Pay?id=d1d1c3eebab04c56b3c3b30c70f2d278
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
    [PaymentId] => d1d1c3eebab04c56b3c3b30c70f2d278
    [PaymentRequestId] => PAYMENT-01
    [OrderNumber] => ORDER-0001
    [POSId] => 56c5aa0ed69b40a39d89756817f83838
    [POSName] => demoShop
    [POSOwnerEmail] => barionaccount@demo-merchant.shop
    [Status] => Prepared
    [PaymentType] => Reservation
    [FundingSource] => 
    [FundingInformation] => FundingInformationModel Object
        (
            [BankCard] => BankCardModel Object
                (
                    [MaskedPan] => 
                    [BankCardType] => 
                    [ValidThruYear] => 
                    [ValidThruMonth] => 
                )

            [AuthorizationCode] => 
        )

    [AllowedFundingSources] => Array
        (
            [0] => All
        )

    [GuestCheckout] => 1
    [CreatedAt] => 2019-08-12T04:38:57.672Z
    [ValidUntil] => 2019-08-12T05:08:57.673Z
    [CompletedAt] => 
    [ReservedUntil] => 2019-08-19T04:38:57.672Z
    [Total] => 1699.9
    [Currency] => EUR
    [Transactions] => Array
        (
            [0] => TransactionDetailModel Object
                (
                    [TransactionId] => 2831dcca202948e1954b91b90a24ca9c
                    [POSTransactionId] => TRANS-01
                    [TransactionTime] => 2019-08-12T04:38:57.739Z
                    [Total] => 1699.9
                    [Currency] => EUR
                    [Payer] => UserModel Object
                        (
                            [Name] => 
                            [Email] => 
                        )

                    [Payee] => UserModel Object
                        (
                            [Name] => 
                            [Email] => barionaccount@demo-merchant.shop
                        )

                    [Comment] => Test transaction containing the products
                    [Status] => Prepared
                    [TransactionType] => Unspecified
                    [Items] => Array
                        (
                            [0] => ItemModel Object
                                (
                                    [Name] => BestPresso Coffee Machine
                                    [Description] => BPC2303 coffee machine, 1-year warranty
                                    [Quantity] => 1
                                    [Unit] => piece
                                    [UnitPrice] => 499.95
                                    [ItemTotal] => 499.95
                                    [SKU] => BPC2303
                                )

                            [1] => ItemModel Object
                                (
                                    [Name] => BestPresso XL Coffee Machine
                                    [Description] => BPC4000 XL professional coffee machine, 3-year warranty
                                    [Quantity] => 1
                                    [Unit] => piece
                                    [UnitPrice] => 1199.95
                                    [ItemTotal] => 1199.95
                                    [SKU] => BPC4000
                                )

                        )

                    [RelatedId] => 
                    [POSId] => 56c5aa0ed69b40a39d89756817f83838
                    [PaymentId] => d1d1c3eebab04c56b3c3b30c70f2d278
                )

            [1] => TransactionDetailModel Object
                (
                    [TransactionId] => 811a6034dadf452db5233b8138b6dd58
                    [POSTransactionId] => 
                    [TransactionTime] => 2019-08-12T04:38:57.759Z
                    [Total] => 17
                    [Currency] => EUR
                    [Payer] => UserModel Object
                        (
                            [Name] => 
                            [Email] => barionaccount@demo-merchant.shop
                        )

                    [Payee] => UserModel Object
                        (
                            [Name] => 
                            [Email] => 
                        )

                    [Comment] => 
                    [Status] => Reserved
                    [TransactionType] => GatewayFee
                    [Items] => Array
                        (
                        )

                    [RelatedId] => 
                    [POSId] => 56c5aa0ed69b40a39d89756817f83838
                    [PaymentId] => d1d1c3eebab04c56b3c3b30c70f2d278
                )

        )

    [RecurrenceResult] => 
    [SuggestedLocale] => en-US
    [FraudRiskScore] => 
    [RedirectUrl] => http://webshop.example.com/afterpayment?paymentId=d1d1c3eebab04c56b3c3b30c70f2d278
    [CallbackUrl] => http://webshop.example.com/processpayment?paymentId=d1d1c3eebab04c56b3c3b30c70f2d278
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
    [PaymentId] => d1d1c3eebab04c56b3c3b30c70f2d278
    [PaymentRequestId] => PAYMENT-01
    [OrderNumber] => ORDER-0001
    [POSId] => 56c5aa0ed69b40a39d89756817f83838
    [POSName] => demoShop
    [POSOwnerEmail] => barionaccount@demo-merchant.shop
    [Status] => Reserved
    [PaymentType] => Reservation
    [FundingSource] => BankCard
    [FundingInformation] => FundingInformationModel Object
        (
            [BankCard] => BankCardModel Object
                (
                    [MaskedPan] => 5559
                    [BankCardType] => Visa
                    [ValidThruYear] => 2023
                    [ValidThruMonth] => 12
                )

            [AuthorizationCode] => x4qqwn
        )

    [AllowedFundingSources] => Array
        (
            [0] => All
        )

    [GuestCheckout] => 1
    [CreatedAt] => 2019-08-12T04:38:57.672Z
    [ValidUntil] => 2019-08-12T05:08:57.673Z
    [CompletedAt] => 
    [ReservedUntil] => 2019-08-19T04:38:57.672Z
    [Total] => 1699.9
    [Currency] => EUR
    [Transactions] => Array
        (
            [0] => TransactionDetailModel Object
                (
                    [TransactionId] => 2831dcca202948e1954b91b90a24ca9c
                    [POSTransactionId] => TRANS-01
                    [TransactionTime] => 2019-08-12T04:38:57.739Z
                    [Total] => 1699.9
                    [Currency] => EUR
                    [Payer] => UserModel Object
                        (
                            [Name] => 
                            [Email] => user@example.com
                        )

                    [Payee] => UserModel Object
                        (
                            [Name] => 
                            [Email] => barionaccount@demo-merchant.shop
                        )

                    [Comment] => Test transaction containing the products
                    [Status] => Reserved
                    [TransactionType] => CardPayment
                    [Items] => Array
                        (
                            [0] => ItemModel Object
                                (
                                    [Name] => BestPresso Coffee Machine
                                    [Description] => BPC2303 coffee machine, 1-year warranty
                                    [Quantity] => 1
                                    [Unit] => piece
                                    [UnitPrice] => 499.95
                                    [ItemTotal] => 499.95
                                    [SKU] => BPC2303
                                )

                            [1] => ItemModel Object
                                (
                                    [Name] => BestPresso XL Coffee Machine
                                    [Description] => BPC4000 XL professional coffee machine, 3-year warranty
                                    [Quantity] => 1
                                    [Unit] => piece
                                    [UnitPrice] => 1199.95
                                    [ItemTotal] => 1199.95
                                    [SKU] => BPC4000
                                )

                        )

                    [RelatedId] => 
                    [POSId] => 56c5aa0ed69b40a39d89756817f83838
                    [PaymentId] => d1d1c3eebab04c56b3c3b30c70f2d278
                )

            [1] => TransactionDetailModel Object
                (
                    [TransactionId] => 811a6034dadf452db5233b8138b6dd58
                    [POSTransactionId] => 
                    [TransactionTime] => 2019-08-12T04:38:57.759Z
                    [Total] => 17
                    [Currency] => EUR
                    [Payer] => UserModel Object
                        (
                            [Name] => 
                            [Email] => barionaccount@demo-merchant.shop
                        )

                    [Payee] => UserModel Object
                        (
                            [Name] => 
                            [Email] => 
                        )

                    [Comment] => 
                    [Status] => Reserved
                    [TransactionType] => GatewayFee
                    [Items] => Array
                        (
                        )

                    [RelatedId] => 
                    [POSId] => 56c5aa0ed69b40a39d89756817f83838
                    [PaymentId] => d1d1c3eebab04c56b3c3b30c70f2d278
                )

        )

    [RecurrenceResult] => 
    [SuggestedLocale] => en-US
    [FraudRiskScore] => 0
    [RedirectUrl] => http://webshop.example.com/afterpayment?paymentId=d1d1c3eebab04c56b3c3b30c70f2d278
    [CallbackUrl] => http://webshop.example.com/processpayment?paymentId=d1d1c3eebab04c56b3c3b30c70f2d278
    [Errors] => Array
        (
        )

    [RequestSuccessful] => 1
)
```

As you can see, the payment status is now **Reserved**, which means the customer has paid the amount. The **FundingInformation** contains info about the bank card that was used.
At this point, the webshop has one week to finish the reservation, as it was previously indicated in the **ReservationPeriod** parameter when the payment was started.

Unfortunately, one of the ordered products turn out to be out of stock and is no longer available. The customer settles with not cancelling the order, just paying only for the other one.
The shop can now finish the reservation by constructing the appropriate **FinishReservationRequestModel** instance:

```php
$item1 = new ItemModel();
$item1->Name = "BestPresso Coffee Machine";
$item1->Description = "BPC2303 coffee machine, 1-year warranty";
$item1->Quantity = 1;
$item1->Unit = "piece";
$item1->UnitPrice = 499.95;
$item1->ItemTotal = 499.95;
$item1->SKU = "BPC2303";

$item2 = new ItemModel();
$item2->Name = "[PRODUCT UNAVAILABLE] BestPresso XL Coffee Machine";
$item2->Description = "[PRODUCT UNAVAILABLE] BPC4000 XL professional coffee machine, 3-year warranty";
$item2->Quantity = 0;
$item2->Unit = "piece";
$item2->UnitPrice = 0;
$item2->ItemTotal = 0;
$item2->SKU = "BPC4000";

$trans = new TransactionToFinishModel();
$trans->TransactionId = $paymentDetails->Transactions[0]->TransactionId;
$trans->Total = 499.95;
$trans->Comment = "Test transaction containing the products";
$trans->AddItem($item1);
$trans->AddItem($item2);

$frrm = new FinishReservationRequestModel($paymentDetails->PaymentId);
$frrm->AddTransaction($trans);
```

The full request for finishing the reservation now looks like this:

```
FinishReservationRequestModel Object
(
    [PaymentId] => d1d1c3eebab04c56b3c3b30c70f2d278
    [Transactions] => Array
        (
            [0] => TransactionToFinishModel Object
                (
                    [TransactionId] => 2831dcca202948e1954b91b90a24ca9c
                    [Total] => 499.95
                    [PayeeTransactions] => Array
                        (
                        )

                    [Items] => Array
                        (
                            [0] => ItemModel Object
                                (
                                    [Name] => BestPresso Coffee Machine
                                    [Description] => BPC2303 coffee machine, 1-year warranty
                                    [Quantity] => 1
                                    [Unit] => piece
                                    [UnitPrice] => 499.95
                                    [ItemTotal] => 499.95
                                    [SKU] => BPC2303
                                )

                            [1] => ItemModel Object
                                (
                                    [Name] => [PRODUCT UNAVAILABLE] BestPresso XL Coffee Machine
                                    [Description] => [PRODUCT UNAVAILABLE] BPC4000 XL professional coffee machine, 3-year warranty
                                    [Quantity] => 0
                                    [Unit] => piece
                                    [UnitPrice] => 0
                                    [ItemTotal] => 0
                                    [SKU] => BPC4000
                                )

                        )

                    [Comment] => Test transaction containing the products
                )

        )

    [POSKey] => 
)
```

Take note of the adjusted **Total** value for the payment, and the indicated unavailability for the second product.

Now the shop can send the request using the **BarionClient**:

```php
$finishReservationResult = $BC->FinishReservation($frrm);
```

When sending the request, the following response arrives:

```
FinishReservationResponseModel Object
(
    [IsSuccessful] => 1
    [PaymentId] => d1d1c3eebab04c56b3c3b30c70f2d278
    [PaymentRequestId] => PAYMENT-01
    [Status] => Succeeded
    [Transactions] => Array
        (
            [0] => TransactionResponseModel Object
                (
                    [POSTransactionId] => TRANS-01
                    [TransactionId] => 2831dcca202948e1954b91b90a24ca9c
                    [Status] => Succeeded
                    [TransactionTime] => 2019-08-12T04:38:57.739Z
                    [RelatedId] => 
                )

            [1] => TransactionResponseModel Object
                (
                    [POSTransactionId] => 
                    [TransactionId] => 811a6034dadf452db5233b8138b6dd58
                    [Status] => Reserved
                    [TransactionTime] => 2019-08-12T04:38:57.759Z
                    [RelatedId] => 
                )

            [2] => TransactionResponseModel Object
                (
                    [POSTransactionId] => 
                    [TransactionId] => b949dc817f5a4f31bfd7d012de7dab8e
                    [Status] => Succeeded
                    [TransactionTime] => 2019-08-12T04:53:25.596Z
                    [RelatedId] => 2
                )

        )

    [Errors] => Array
        (
        )

    [RequestSuccessful] => 1
)
```

This shows that everything went okay, the request was successful. The reservation is now finished.

If the webshop requests the payment details once more, you can see the completed state:

```
PaymentStateResponseModel Object
(
    [PaymentId] => d1d1c3eebab04c56b3c3b30c70f2d278
    [PaymentRequestId] => PAYMENT-01
    [OrderNumber] => ORDER-0001
    [POSId] => 56c5aa0ed69b40a39d89756817f83838
    [POSName] => demoShop
    [POSOwnerEmail] => barionaccount@demo-merchant.shop
    [Status] => Succeeded
    [PaymentType] => Reservation
    [FundingSource] => BankCard
    [FundingInformation] => FundingInformationModel Object
        (
            [BankCard] => BankCardModel Object
                (
                    [MaskedPan] => 5559
                    [BankCardType] => Visa
                    [ValidThruYear] => 2023
                    [ValidThruMonth] => 12
                )

            [AuthorizationCode] => x4qqwn
        )

    [AllowedFundingSources] => Array
        (
            [0] => All
        )

    [GuestCheckout] => 1
    [CreatedAt] => 2019-08-12T04:38:57.672Z
    [ValidUntil] => 2019-08-12T05:08:57.673Z
    [CompletedAt] => 2019-08-12T04:53:25.625Z
    [ReservedUntil] => 2019-08-19T04:38:57.672Z
    [Total] => 499.95
    [Currency] => EUR
    [Transactions] => Array
        (
            [0] => TransactionDetailModel Object
                (
                    [TransactionId] => 2831dcca202948e1954b91b90a24ca9c
                    [POSTransactionId] => TRANS-01
                    [TransactionTime] => 2019-08-12T04:38:57.739Z
                    [Total] => 1699.9
                    [Currency] => EUR
                    [Payer] => UserModel Object
                        (
                            [Name] => 
                            [Email] => user@example.com
                        )

                    [Payee] => UserModel Object
                        (
                            [Name] => 
                            [Email] => barionaccount@demo-merchant.shop
                        )

                    [Comment] => Test transaction containing the products
                    [Status] => Succeeded
                    [TransactionType] => CardPayment
                    [Items] => Array
                        (
                            [0] => ItemModel Object
                                (
                                    [Name] => BestPresso Coffee Machine
                                    [Description] => BPC2303 coffee machine, 1-year warranty
                                    [Quantity] => 1
                                    [Unit] => piece
                                    [UnitPrice] => 499.95
                                    [ItemTotal] => 499.95
                                    [SKU] => BPC2303
                                )

                            [1] => ItemModel Object
                                (
                                    [Name] => [PRODUCT UNAVAILABLE] BestPresso XL Coffee Machine
                                    [Description] => [PRODUCT UNAVAILABLE] BPC4000 XL professional coffee machine, 3-year warranty
                                    [Quantity] => 0
                                    [Unit] => piece
                                    [UnitPrice] => 0
                                    [ItemTotal] => 0
                                    [SKU] => BPC4000
                                )

                        )

                    [RelatedId] => 
                    [POSId] => 56c5aa0ed69b40a39d89756817f83838
                    [PaymentId] => d1d1c3eebab04c56b3c3b30c70f2d278
                )

            [1] => TransactionDetailModel Object
                (
                    [TransactionId] => 811a6034dadf452db5233b8138b6dd58
                    [POSTransactionId] => 
                    [TransactionTime] => 2019-08-12T04:38:57.759Z
                    [Total] => 17
                    [Currency] => EUR
                    [Payer] => UserModel Object
                        (
                            [Name] => 
                            [Email] => barionaccount@demo-merchant.shop
                        )

                    [Payee] => UserModel Object
                        (
                            [Name] => 
                            [Email] => 
                        )

                    [Comment] => 
                    [Status] => Reserved
                    [TransactionType] => GatewayFee
                    [Items] => Array
                        (
                        )

                    [RelatedId] => 
                    [POSId] => 56c5aa0ed69b40a39d89756817f83838
                    [PaymentId] => d1d1c3eebab04c56b3c3b30c70f2d278
                )

            [2] => TransactionDetailModel Object
                (
                    [TransactionId] => b949dc817f5a4f31bfd7d012de7dab8e
                    [POSTransactionId] => 
                    [TransactionTime] => 2019-08-12T04:53:25.596Z
                    [Total] => 1199.95
                    [Currency] => EUR
                    [Payer] => UserModel Object
                        (
                            [Name] => 
                            [Email] => barionaccount@demo-merchant.shop
                        )

                    [Payee] => UserModel Object
                        (
                            [Name] => 
                            [Email] => user@example.com
                        )

                    [Comment] => 
                    [Status] => Succeeded
                    [TransactionType] => RefundToBankCard
                    [Items] => Array
                        (
                        )

                    [RelatedId] => 2831dcca202948e1954b91b90a24ca9c
                    [POSId] => 56c5aa0ed69b40a39d89756817f83838
                    [PaymentId] => d1d1c3eebab04c56b3c3b30c70f2d278
                )

        )

    [RecurrenceResult] => 
    [SuggestedLocale] => en-US
    [FraudRiskScore] => 0
    [RedirectUrl] => http://webshop.example.com/afterpayment?paymentId=d1d1c3eebab04c56b3c3b30c70f2d278
    [CallbackUrl] => http://webshop.example.com/processpayment?paymentId=d1d1c3eebab04c56b3c3b30c70f2d278
    [Errors] => Array
        (
        )

    [RequestSuccessful] => 1
)
```

Note that there is a **RefundToBankCard** transaction in the payment. This is because one of the products were unavailable and the webshop finished the reservation with a smaller amount than what the customer originally paid. The Barion system automatically refunded the difference to the customer's bank card.