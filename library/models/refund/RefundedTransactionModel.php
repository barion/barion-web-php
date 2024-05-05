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

use Barion\Helpers\JSON;

use Barion\Enumerations\{
    TransactionStatus
};

/**
 * Model containing information that was refunded via the Barion system.
 */
class RefundedTransactionModel implements \Barion\Interfaces\IBarionModel 
{
    /** 
     * The Barion identifier of the refunded transaction.
     * 
     * @var ?string
     */
    public ?string $TransactionId;

    /** 
     * The total amount of the refunded transaction.
     * 
     * @var ?float
     */
    public ?float $Total;

    /** 
     * The internal identifier of the refunded transaction, specified by the shop when starting the payment.
     * 
     * @var ?string
     */
    public ?string $POSTransactionId;

    /** 
     * The comment for the refund.
     * 
     * @var ?string
     */
    public ?string $Comment;

    /** 
     * The status of the refunded transaction.
     * 
     * @var TransactionStatus
     */
    public TransactionStatus $Status;

    function __construct()
    {
        $this->TransactionId = "";
        $this->Total = 0.0;
        $this->POSTransactionId = null;
        $this->Comment = null;
        $this->Status = TransactionStatus::Unknown;
    }

    public function fromJson(array $json) : void
    {
        if (!empty($json)) {
            $this->TransactionId = JSON::getString($json, 'TransactionId');
            $this->Total = JSON::getFloat($json, 'Total');
            $this->POSTransactionId = JSON::getString($json, 'POSTransactionId');
            $this->Comment = JSON::getString($json, 'Comment');
            $this->Status = TransactionStatus::from(JSON::getString($json, 'Status') ?? '');
        }
    }
}