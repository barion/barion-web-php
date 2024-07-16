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
 * Model describing a payee transaction that is being captured in a delayed capture scenario.
 */
class PayeeTransactionToCaptureModel
{
    /** 
     * The Barion identifier of the payee transaction.
     * 
     * @var string
     */
    public string $TransactionId;

    /** 
     * The total finishing amount of the payee transaction.
     * 
     * @var float
     */
    public float $Total;

    /** 
     * Optional comment for the finished payee transaction.
     * 
     * @var ?string
     */
    public ?string $Comment;

    function __construct()
    {
        $this->TransactionId = "";
        $this->Total = 0.0;
        $this->Comment = null;
    }
}
