# Example - delayed capture

In this example we are going to start a delayed capture payment, where the amount gets "blocked" on the customer's credit card, and then captured by the webshop the next day.

### 1. Creating the request object

To start an online payment, you have to create one or more **Payment Transaction** objects, add transaction **Items** to them, then group these transactions together in a **Payment** object.

First, create an **ItemModel** instance:

```php
$item = new ItemModel();
$item->Name = "J.K. Rowling - Harry Potter and the chamber of secrets";
$item->Description = "Second part of best-selling author J.K. Rowling's magnificient wizard tale about young Harry Potter's adventures at Hogwarts.";
$item->Quantity = 1;
$item->Unit = "piece";
$item->UnitPrice = 9.99;
$item->ItemTotal = 9.99;
$item->SKU = "HPbk2";
```

Then create a **PaymentTransactionModel** and add the two **ItemModel** instances mentioned above to it:

```php
$trans = new PaymentTransactionModel();
$trans->POSTransactionId = "TRANS-01";
$trans->Payee = "barionaccount@demo-merchant.shop";
$trans->Total = 9.99;
$trans->Currency = Currency::EUR;
$trans->Comment = "Test transaction containing the product";
$trans->AddItem($item);
```

Finally, create a **PreparePaymentRequestModel** and add the **PaymentTransactionModel** mentioned above to it:

```php
$ppr = new PreparePaymentRequestModel();
$ppr->GuestCheckout = true;
$ppr->PaymentType = PaymentType::DelayedCapture;
$ppr->DelayedCapturePeriod = "1.00:00:00";
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
    [PaymentType] => DelayedCapture
    [ReservationPeriod] => 
    [DelayedCapturePeriod] => 1.00:00:00
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
                    [Total] => 9.99
                    [Comment] => Test transaction containing the product
                    [Items] => Array
                        (
                            [0] => ItemModel Object
                                (
                                    [Name] => J.K. Rowling - Harry Potter and the chamber of secrets
                                    [Description] => Second part of best-selling author J.K. Rowling's magnificient wizard tale about young Harry Potter's adventures at Hogwarts.
                                    [Quantity] => 1
                                    [Unit] => piece
                                    [UnitPrice] => 9.99
                                    [ItemTotal] => 9.99
                                    [SKU] => HPbk2
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
    [PaymentId] => 6faf16e245e44bc0b60aebad6aeb9ec2
    [PaymentRequestId] => PAYMENT-01
    [Status] => Prepared
    [Transactions] => Array
        (
            [0] => TransactionResponseModel Object
                (
                    [POSTransactionId] => TRANS-01
                    [TransactionId] => 277edf17c7ce468a83f538102b4109be
                    [Status] => Prepared
                    [TransactionTime] => 2019-08-12T05:07:57.7548745Z
                    [RelatedId] => 
                )

        )

    [QRUrl] => https://api.barion.com/qr/generate?paymentId=6faf16e2-45e4-4bc0-b60a-ebad6aeb9ec2&size=Large
    [RecurrenceResult] => None
    [PaymentRedirectUrl] => https://secure.barion.com/Pay?id=6faf16e245e44bc0b60aebad6aeb9ec2
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
    [PaymentId] => 6faf16e245e44bc0b60aebad6aeb9ec2
    [PaymentRequestId] => PAYMENT-01
    [OrderNumber] => ORDER-0001
    [POSId] => 56c5aa0ed69b40a39d89756817f83838
    [POSName] => demoShop
    [POSOwnerEmail] => barionaccount@demo-merchant.shop
    [Status] => Prepared
    [PaymentType] => DelayedCapture
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
    [CreatedAt] => 2019-08-12T05:07:57.754Z
    [ValidUntil] => 2019-08-12T05:37:57.754Z
    [CompletedAt] => 
    [ReservedUntil] => 
    [Total] => 9.99
    [Currency] => EUR
    [Transactions] => Array
        (
            [0] => TransactionDetailModel Object
                (
                    [TransactionId] => 277edf17c7ce468a83f538102b4109be
                    [POSTransactionId] => TRANS-01
                    [TransactionTime] => 2019-08-12T05:07:57.755Z
                    [Total] => 9.99
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

                    [Comment] => Test transaction containing the product
                    [Status] => Prepared
                    [TransactionType] => Unspecified
                    [Items] => Array
                        (
                            [0] => ItemModel Object
                                (
                                    [Name] => J.K. Rowling - Harry Potter and the chamber of secrets
                                    [Description] => Second part of best-selling author J.K. Rowling's magnificient wizard tale about young Harry Potter's adventures at Hogwarts.
                                    [Quantity] => 1
                                    [Unit] => piece
                                    [UnitPrice] => 9.99
                                    [ItemTotal] => 9.99
                                    [SKU] => HPbk2
                                )

                        )

                    [RelatedId] => 
                    [POSId] => 56c5aa0ed69b40a39d89756817f83838
                    [PaymentId] => 6faf16e245e44bc0b60aebad6aeb9ec2
                )

        )

    [RecurrenceResult] => 
    [SuggestedLocale] => en-US
    [FraudRiskScore] => 
    [RedirectUrl] => http://webshop.example.com/afterpayment?paymentId=6faf16e245e44bc0b60aebad6aeb9ec2
    [CallbackUrl] => http://webshop.example.com/processpayment?paymentId=6faf16e245e44bc0b60aebad6aeb9ec2
    [Errors] => Array
        (
        )

    [RequestSuccessful] => 1
)
FinishReservationRequestModel Object
(
    [PaymentId] => 6faf16e245e44bc0b60aebad6aeb9ec2
    [Transactions] => Array
        (
            [0] => TransactionToFinishModel Object
                (
                    [TransactionId] => 277edf17c7ce468a83f538102b4109be
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

                    [Comment] => Test transaction containing the product
                )

        )

    [POSKey] => 
)
```

The payment is in **Prepared** status, which means it is still waiting to be paid.
If we wait until the user completes the payment, and send the request again, we should get a slightly different result with more information:

```
PaymentStateResponseModel Object
(
    [PaymentId] => 6faf16e245e44bc0b60aebad6aeb9ec2
    [PaymentRequestId] => PAYMENT-01
    [OrderNumber] => ORDER-0001
    [POSId] => 56c5aa0ed69b40a39d89756817f83838
    [POSName] => demoShop
    [POSOwnerEmail] => barionaccount@demo-merchant.shop
    [Status] => Authorized
    [PaymentType] => DelayedCapture
    [FundingSource] => BankCard
    [FundingInformation] => FundingInformationModel Object
        (
            [BankCard] => BankCardModel Object
                (
                    [MaskedPan] => 5559
                    [BankCardType] => Visa
                    [ValidThruYear] => 2022
                    [ValidThruMonth] => 12
                )

            [AuthorizationCode] => 76h20m
        )

    [AllowedFundingSources] => Array
        (
            [0] => All
        )

    [GuestCheckout] => 1
    [CreatedAt] => 2019-08-12T05:07:57.754Z
    [ValidUntil] => 2019-08-12T05:37:57.754Z
    [CompletedAt] => 
    [ReservedUntil] => 
    [Total] => 9.99
    [Currency] => EUR
    [Transactions] => Array
        (
            [0] => TransactionDetailModel Object
                (
                    [TransactionId] => 277edf17c7ce468a83f538102b4109be
                    [POSTransactionId] => TRANS-01
                    [TransactionTime] => 2019-08-12T05:07:57.755Z
                    [Total] => 9.99
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
                    [Status] => Authorized
                    [TransactionType] => CardPayment
                    [Items] => Array
                        (
                            [0] => ItemModel Object
                                (
                                    [Name] => J.K. Rowling - Harry Potter and the chamber of secrets
                                    [Description] => Second part of best-selling author J.K. Rowling's magnificient wizard tale about young Harry Potter's adventures at Hogwarts.
                                    [Quantity] => 1
                                    [Unit] => piece
                                    [UnitPrice] => 9.99
                                    [ItemTotal] => 9.99
                                    [SKU] => HPbk2
                                )

                        )

                    [RelatedId] => 
                    [POSId] => 56c5aa0ed69b40a39d89756817f83838
                    [PaymentId] => 6faf16e245e44bc0b60aebad6aeb9ec2
                )

        )

    [RecurrenceResult] => 
    [SuggestedLocale] => en-US
    [FraudRiskScore] => 0
    [RedirectUrl] => http://webshop.example.com/afterpayment?paymentId=6faf16e245e44bc0b60aebad6aeb9ec2
    [CallbackUrl] => http://webshop.example.com/processpayment?paymentId=6faf16e245e44bc0b60aebad6aeb9ec2
    [Errors] => Array
        (
        )

    [RequestSuccessful] => 1
)
```

As you can see, the payment status is now **Authorized**, which means the customer has paid the amount and it is blocked on the bank card.
At this point, the webshop has one day to capture the amount, as it was previously indicated in the **DelayedCapturePeriod** parameter when the payment was started.

The shop can capture the amount by constructing the appropriate **CaptureRequestModel** instance:

```php
$item = new ItemModel();
$item->Name = "J.K. Rowling - Harry Potter and the chamber of secrets";
$item->Description = "Second part of best-selling author J.K. Rowling's magnificient wizard tale about young Harry Potter's adventures at Hogwarts.";
$item->Quantity = 1;
$item->Unit = "piece";
$item->UnitPrice = 9.99;
$item->ItemTotal = 9.99;
$item->SKU = "HPbk2";

$trans = new TransactionToCaptureModel();
$trans->TransactionId = $paymentDetails->Transactions[0]->TransactionId;
$trans->Total = 9.99;
$trans->Comment = "Test transaction containing the product";
$trans->AddItem($item);

$crm = new CaptureRequestModel($paymentDetails->PaymentId);
$crm->AddTransaction($trans);
```

The full request for capturing the amount looks like this:

```
CaptureRequestModel Object
(
    [PaymentId] => 6faf16e245e44bc0b60aebad6aeb9ec2
    [Transactions] => Array
        (
            [0] => TransactionToCaptureModel Object
                (
                    [TransactionId] => 277edf17c7ce468a83f538102b4109be
                    [Total] => 9.99
                    [PayeeTransactions] => Array
                        (
                        )

                    [Items] => Array
                        (
                            [0] => ItemModel Object
                                (
                                    [Name] => J.K. Rowling - Harry Potter and the chamber of secrets
                                    [Description] => Second part of best-selling author J.K. Rowling's magnificient wizard tale about young Harry Potter's adventures at Hogwarts.
                                    [Quantity] => 1
                                    [Unit] => piece
                                    [UnitPrice] => 9.99
                                    [ItemTotal] => 9.99
                                    [SKU] => HPbk2
                                )

                        )

                    [Comment] => Test transaction containing the product
                )

        )

    [POSKey] => 
)
```
Now the shop can send the request using the **BarionClient**:

```php
$captureResult = $BC->Capture($crm);
```

When sending the request, the following response arrives:

```
CaptureResponseModel Object
(
    [IsSuccessful] => 1
    [PaymentId] => 6faf16e245e44bc0b60aebad6aeb9ec2
    [PaymentRequestId] => PAYMENT-01
    [Status] => Succeeded
    [Transactions] => Array
        (
            [0] => TransactionResponseModel Object
                (
                    [POSTransactionId] => TRANS-01
                    [TransactionId] => 277edf17c7ce468a83f538102b4109be
                    [Status] => Succeeded
                    [TransactionTime] => 2019-08-12T05:17:55.824
                    [RelatedId] => 
                )

            [1] => TransactionResponseModel Object
                (
                    [POSTransactionId] => 
                    [TransactionId] => 0d5645a581654b8b940ddcefff41ce73
                    [Status] => Reserved
                    [TransactionTime] => 2019-08-12T05:17:55.579
                    [RelatedId] => 
                )

        )

    [Errors] => Array
        (
        )

    [RequestSuccessful] => 1
)
```

This shows that everything went okay, the request was successful. The amount has been captured. It is now deducted from the user's funding source and credited in the webshop's Barion account.

If the webshop requests the payment details once more, you can see the completed state:

```
PaymentStateResponseModel Object
(
    [PaymentId] => 6faf16e245e44bc0b60aebad6aeb9ec2
    [PaymentRequestId] => PAYMENT-01
    [OrderNumber] => ORDER-0001
    [POSId] => 56c5aa0ed69b40a39d89756817f83838
    [POSName] => demoShop
    [POSOwnerEmail] => barionaccount@demo-merchant.shop
    [Status] => Succeeded
    [PaymentType] => DelayedCapture
    [FundingSource] => BankCard
    [FundingInformation] => FundingInformationModel Object
        (
            [BankCard] => BankCardModel Object
                (
                    [MaskedPan] => 5559
                    [BankCardType] => Visa
                    [ValidThruYear] => 2022
                    [ValidThruMonth] => 12
                )

            [AuthorizationCode] => 6qtub8
        )

    [AllowedFundingSources] => Array
        (
            [0] => All
        )

    [GuestCheckout] => 1
    [CreatedAt] => 2019-08-12T05:07:57.754Z
    [ValidUntil] => 2019-08-12T05:37:57.754Z
    [CompletedAt] => 2019-08-12T05:17:55.824Z
    [ReservedUntil] => 
    [Total] => 9.99
    [Currency] => EUR
    [Transactions] => Array
        (
            [0] => TransactionDetailModel Object
                (
                    [TransactionId] => 277edf17c7ce468a83f538102b4109be
                    [POSTransactionId] => TRANS-01
                    [TransactionTime] => 2019-08-12T05:07:57.755Z
                    [Total] => 9.99
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

                    [Comment] => Test transaction containing the product
                    [Status] => Succeeded
                    [TransactionType] => CardPayment
                    [Items] => Array
                        (
                            [0] => ItemModel Object
                                (
                                    [Name] => J.K. Rowling - Harry Potter and the chamber of secrets
                                    [Description] => Second part of best-selling author J.K. Rowling's magnificient wizard tale about young Harry Potter's adventures at Hogwarts.
                                    [Quantity] => 1
                                    [Unit] => piece
                                    [UnitPrice] => 9.99
                                    [ItemTotal] => 9.99
                                    [SKU] => HPbk2
                                )

                        )

                    [RelatedId] => 
                    [POSId] => 56c5aa0ed69b40a39d89756817f83838
                    [PaymentId] => 6faf16e245e44bc0b60aebad6aeb9ec2
                )

            [1] => TransactionDetailModel Object
                (
                    [TransactionId] => 0d5645a581654b8b940ddcefff41ce73
                    [POSTransactionId] => 
                    [TransactionTime] => 2019-08-12T05:17:55.565Z
                    [Total] => 0.1
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
                    [PaymentId] => 6faf16e245e44bc0b60aebad6aeb9ec2
                )

        )

    [RecurrenceResult] => 
    [SuggestedLocale] => en-US
    [FraudRiskScore] => 0
    [RedirectUrl] => http://webshop.example.com/afterpayment?paymentId=6faf16e245e44bc0b60aebad6aeb9ec2
    [CallbackUrl] => http://webshop.example.com/processpayment?paymentId=6faf16e245e44bc0b60aebad6aeb9ec2
    [Errors] => Array
        (
        )

    [RequestSuccessful] => 1
)
```
