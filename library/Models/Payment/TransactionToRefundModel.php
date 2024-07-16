<?php

/**
 * Copyright 2024 Barion Payment Inc. All Rights Reserved.
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
 * Model describing a transaction that is being refunded.
 */
class TransactionToRefundModel
{
    /** 
     * The Barion identifier of the transaction.
     * 
     * @var string
     */ 
    public string $TransactionId;

    /** 
     * The internal identifier of the transaction, specified by the shop.
     * 
     * @var string
     */ 
    public string $POSTransactionId;
    
    /** 
     * The amount to refund from the transaction.
     * 
     * @var float
     */ 
    public float $AmountToRefund;
    
    /** 
     * Optional comment of the refund transaction.
     * 
     * @var ?string
     */ 
    public ?string $Comment;

    function __construct(string $transactionId, string $posTransactionId, float $amountToRefund, string $comment = null)
    {
        $this->TransactionId = $transactionId;
        $this->POSTransactionId = $posTransactionId;
        $this->AmountToRefund = $amountToRefund;
        $this->Comment = $comment;
    }
}