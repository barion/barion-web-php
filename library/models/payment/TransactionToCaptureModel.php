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
 * Model describing a transaction that is being captured in a delayed capture scenario.
 */
class TransactionToCaptureModel
{
    /** 
     * The Barion identifier of the transaction.
     * 
     * @var string
     */  
    public string $TransactionId;

    /** 
     * The total amount to capture in the transaction.
     * 
     * @var float
     */
    public float $Total;
    
    /** 
     * Payee transactions attached to the transaction.
     * 
     * @var array<object> 
     */
    public array $PayeeTransactions;
    
     /**
      * Items included in the transaction.
      *
      * @var array<object> 
      */
    public array $Items;
    
    /** 
     * Optional comment of the transaction.
     * 
     * @var ?string
     */ 
    public ?string $Comment;

    function __construct()
    {
        $this->TransactionId = "";
        $this->Total = 0.0;
        $this->PayeeTransactions = array();
        $this->Comment = null;
        $this->Items = array();
    }
    
    /**
     * Add a single item to the transaction.
     *
     * @param ItemModel $item
     * @return void
     */
    public function AddItem(ItemModel $item) : void
    {
        array_push($this->Items, $item);
    }

    /** 
     * Add multiple items to the transaction. 
     * 
     * @param array<object> $items
     * @return void
    */
    public function AddItems(array $items) : void
    {
        foreach ($items as $item) {
            if ($item instanceof ItemModel) {
                $this->AddItem($item);
            }
        }
    }

    /**
     * Add a single payee transaction to the transaction.
     *
     * @param PayeeTransactionToCaptureModel $model Model describing the payee transaction to be captured.
     * @return void
     */    
    public function AddPayeeTransaction(PayeeTransactionToCaptureModel $model) : void
    {
        array_push($this->PayeeTransactions, $model);
    }

    /** 
     * Add multiple payee transactions to the transaction. 
     * 
     * @param array<object> $transactions
     * @return void
    */
    public function AddPayeeTransactions(array $transactions) : void
    {
        foreach ($transactions as $transaction) {
            if ($transaction instanceof PayeeTransactionToCaptureModel) {
                $this->AddPayeeTransaction($transaction);
            }
        }
    }
}