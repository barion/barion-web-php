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

namespace Barion\Models\Payment;

/**
 * Model used to request to capture the amount of a previously authorized payment in a delayed capture scenario.
 */
class CaptureRequestModel extends \Barion\Models\BaseRequestModel
{
    /** 
     * The Barion identifier of the payment.
     * 
     * @var string
     */
    public string $PaymentId;
    
    /** 
     * Array of previously authorized payment transactions that are to be captured.
     * 
     * @var array<object>
    */
    public array $Transactions;

    function __construct(string $paymentId)
    {
        $this->PaymentId = $paymentId;
        $this->Transactions = array();
    }
    
    /**
     * Add a single transaction to the capture request.
     *
     * @param TransactionToCaptureModel $transaction Model describing the transaction to be captured.
     * @return void
     */
    public function AddTransaction(TransactionToCaptureModel $transaction) : void
    {
        if ($this->Transactions == null) {
            $this->Transactions = array();
        }
        array_push($this->Transactions, $transaction);
    }

    /** @param array<object> $transactions */
    public function AddTransactions(array $transactions) : void
    {
        if (!empty($transactions)) {
            foreach ($transactions as $transaction) {
                if ($transaction instanceof TransactionToCaptureModel) {
                    $this->AddTransaction($transaction);
                }
            }
        }
    }

}