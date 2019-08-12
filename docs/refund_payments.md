# Example - refund a payment

In this example a payment is refunded to the customer.

Consider a previously completed, successful payment:

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

Unfortunately, the product has been damaged during shipping, so the customer demands a refund.

The webshop can easily manage this with a simple API call.

### 1. Creating the request object

First, construct the **TransactionToRefundModel** accordingly:

```php
$trans = new TransactionToRefundModel();
$trans->TransactionId = "277edf17c7ce468a83f538102b4109be";
$trans->POSTransactionId = "TRANS-01";
$trans->AmountToRefund = 9.99;
$trans->Comment = "Refund of ORDER-0001 upon customer complaint";
```

Then, create a **RefundRequestModel** and add the transaction to it:

```php
$rr = new RefundRequestModel($paymentId);
$rr->AddTransaction($trans);
```

At this point, the complete refund request object looks like this:

```
RefundRequestModel Object
(
    [PaymentId] => 6faf16e245e44bc0b60aebad6aeb9ec2
    [TransactionsToRefund] => Array
        (
            [0] => TransactionToRefundModel Object
                (
                    [TransactionId] => 277edf17c7ce468a83f538102b4109be
                    [POSTransactionId] => TRANS-01
                    [AmountToRefund] => 9.99
                    [Comment] => Refund of ORDER-0001 upon customer complaint
                )

        )

    [POSKey] => 
)
```
**Note:** the secret *POSKey* used for authentication is not part of the request object.
The Barion client class automatically injects this value into every request sent to the Barion API.

### 2. Calling the Barion API

Now you can call the **RefundPayment** method of the Barion client with the request model you just created:

```php
$refundResult = $BC->RefundPayment($rr);
```

The Barion API now refunds the payment.

The response in the **refundResult** variable should look something like this:

```
RefundResponseModel Object
(
    [PaymentId] => 6faf16e245e44bc0b60aebad6aeb9ec2
    [RefundedTransactions] => Array
        (
            [0] => RefundedTransactionModel Object
                (
                    [TransactionId] => a173eb5e96fd4bb38488968b6ca264e2
                    [Total] => 9.99
                    [POSTransactionId] => TRANS-01
                    [Comment] => Refund of ORDER-0001 upon customer complaint
                    [Status] => Succeeded
                )

        )

    [Errors] => Array
        (
        )

    [RequestSuccessful] => 1
)
```
The **RequestSuccessful** parameter shows that the request was successfully sent, and the response was successfully received. 
As you can see, the **RefundedTransactions** array contains the refund transaction, with its status being **Succeeded**. This means the amount has been refunded successfully, and the customer got their money back.

If we now request the payment details again, we can see that the structure changed slightly:

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
    [Total] => 0
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

            [2] => TransactionDetailModel Object
                (
                    [TransactionId] => a173eb5e96fd4bb38488968b6ca264e2
                    [POSTransactionId] => TRANS-01
                    [TransactionTime] => 2019-08-12T05:32:44.568Z
                    [Total] => 9.99
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

                    [Comment] => Refund of ORDER-0001 upon customer complaint
                    [Status] => Succeeded
                    [TransactionType] => RefundToBankCard
                    [Items] => Array
                        (
                        )

                    [RelatedId] => 277edf17c7ce468a83f538102b4109be
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

The payment **Total** now shows 0 because the refund took place, and the webshop refunded the complete amount.
In the **Transactions** array, a new transaction appeared, which is the refund the webshop just did. The **RelatedId** property of the refund transaction contains the identifier of the initial transaction in the payment that has been refunded.