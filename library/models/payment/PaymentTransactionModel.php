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

class PaymentTransactionModel
{
    public string $POSTransactionId;
    public string $Payee;
    public float $Total;
    public ?string $Comment;
    public array $Items;
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

    public function AddItem(\Barion\Models\Common\ItemModel $item)
    {
        array_push($this->Items, $item);
    }

    public function AddItems($items)
    {
        foreach ($items as $item) {
            if ($item instanceof \Barion\Models\Common\ItemModel) {
                $this->AddItem($item);
            }
        }
    }

    public function AddPayeeTransaction(PayeeTransactionModel $model)
    {
        array_push($this->PayeeTransactions, $model);
    }

    public function AddPayeeTransactions($transactions)
    {
        foreach ($transactions as $transaction) {
            if ($transaction instanceof PayeeTransactionModel) {
                $this->AddPayeeTransaction($transaction);
            }
        }
    }
}