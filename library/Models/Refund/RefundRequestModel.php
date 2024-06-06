<?php

/**
 * Copyright 2016 Barion Payment Inc. All Rights Reserved.
 * <p/>
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 * <p/>
 * http://www.apache.org/licenses/LICENSE-2.0
 * <p/>
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

namespace Barion\Models\Refund;

use Barion\Models\BaseRequestModel;
use Barion\Interfaces\IPaymentTransactionContainer;
use Barion\Models\Payment\{
    TransactionToRefundModel
};

/**
 * Model used to request the refund of a previously completed payment transaction.
 */
class RefundRequestModel extends BaseRequestModel implements IPaymentTransactionContainer
{
    /** 
     * The Barion identifier of the payment.
     * 
     * @var string
     */
    public string $PaymentId;
    
    /** 
     * Array of transactions in the payment that are to be refunded.
     * 
     * @var array<object> 
     */
    public array $TransactionsToRefund;

    function __construct(string $paymentId)
    {
        $this->PaymentId = $paymentId;
        $this->TransactionsToRefund = array();
    }
    
    /**
     * Add a single transaction to the refund request.
     *
     * @param TransactionToRefundModel $transaction Model describing the transaction to be refunded.
     * @return void
     */
    public function AddTransaction(TransactionToRefundModel $transaction) : void
    {
        $this->TransactionsToRefund[] = $transaction;
    }

    /**
     * Add multiple transactions to the refund request.
     *  
     * @param array<object> $transactions
     * @return void
    */
    public function AddTransactions(array $transactions) : void
    {
        if (!empty($transactions)) {
            foreach ($transactions as $transaction) {
                if ($transaction instanceof TransactionToRefundModel) {
                    $this->AddTransaction($transaction);
                }
            }
        }
    }

}