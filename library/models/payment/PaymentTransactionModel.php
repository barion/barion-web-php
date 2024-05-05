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

use Barion\Models\Common\{
    ItemModel
};

/**
 * Model containing details about a payment transaction attached to a Barion Smart Gateway payment.
 */
class PaymentTransactionModel
{
    /** 
     * The internal identifier of the transaction, specified by the shop.
     * 
     * @var string
     */
    public string $POSTransactionId;

    /** 
     * The e-mail address of the recipient of the payment amount.
     * 
     * @var string
     */
    public string $Payee;

    /** 
     * The total amount of the payment.
     * 
     * @var float
     */
    public float $Total;

    /** 
     * Optional comment of the payment transaction.
     * 
     * @var ?string
     */
    public ?string $Comment;
    
    /** 
     * Array of items included in the transaction.
     * 
     * @var array<object> 
     */
    public array $Items;
    
    /** 
     * Array of payee transactions attached to the transaction.
     * 
     * @var array<object> 
     */
    public array $PayeeTransactions;

    function __construct()
    {
        $this->POSTransactionId = "";
        $this->Payee = "";
        $this->Total = 0.0;
        $this->Comment = null;
        $this->Items = array();
        $this->PayeeTransactions = array();
    }
        
    /**
     * Add a single item to the payment transaction.
     *
     * @param ItemModel $item
     * @return void
     */
    public function AddItem(ItemModel $item) : void
    {
        array_push($this->Items, $item);
    }

    /** @param array<object> $items */
    public function AddItems(array $items) : void
    {
        foreach ($items as $item) {
            if ($item instanceof ItemModel) {
                $this->AddItem($item);
            }
        }
    }

    /**
     * Attach a single payee transaction to the payment transaction.
     *
     * @param PayeeTransactionModel $model
     * @return void
     */
    public function AddPayeeTransaction(PayeeTransactionModel $model) : void
    {
        array_push($this->PayeeTransactions, $model);
    }

    /** @param array<object> $transactions */
    public function AddPayeeTransactions(array $transactions) : void
    {
        foreach ($transactions as $transaction) {
            if ($transaction instanceof PayeeTransactionModel) {
                $this->AddPayeeTransaction($transaction);
            }
        }
    }
}