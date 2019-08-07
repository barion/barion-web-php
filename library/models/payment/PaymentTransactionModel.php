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
class PaymentTransactionModel
{
    public $POSTransactionId;
    public $Payee;
    public $Total;
    public $Comment;
    public $Items;
    public $PayeeTransactions;

    function __construct()
    {
        $this->POSTransactionId = "";
        $this->Payee = "";
        $this->Total = 0;
        $this->Comment = "";
        $this->Items = array();
        $this->PayeeTransactions = array();
    }

    public function AddItem(ItemModel $item)
    {
        if ($this->Items == null) {
            $this->Items = array();
        }
        array_push($this->Items, $item);
    }

    public function AddItems($items)
    {
        if (!empty($items)) {
            foreach ($items as $item) {
                if ($item instanceof ItemModel) {
                    $this->AddItem($item);
                }
            }
        }
    }

    public function AddPayeeTransaction(PayeeTransactionModel $model)
    {
        if ($this->PayeeTransactions == null) {
            $this->PayeeTransactions = array();
        }
        array_push($this->PayeeTransactions, $model);
    }

    public function AddPayeeTransactions($transactions)
    {
        if (!empty($transactions)) {
            foreach ($transactions as $transaction) {
                if ($transaction instanceof PayeeTransactionModel) {
                    $this->AddPayeeTransaction($transaction);
                }
            }
        }
    }
}