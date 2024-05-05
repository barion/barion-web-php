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

use Barion\Helpers\JSON;

use Barion\Enumerations\{
    TransactionStatus
};

/**
 *  Model containing detailed information about a payment transaction in a Barion API response.
 */
class TransactionResponseModel implements \Barion\Interfaces\IBarionModel
{
    /** 
     * The internal identifier of the transaction, specified by the shop.
     * 
     * @var ?string
     */   
    public ?string $POSTransactionId;

    /** 
     * The Barion identifier of the transaction.
     * 
     * @var ?string
     */    
    public ?string $TransactionId;

    /** 
     * Current status of the transaction.
     * 
     * @var TransactionStatus
     */ 
    public TransactionStatus $Status;

    /** 
     * ISO-8601 format timestamp of the transaction.
     * 
     * @var ?string
     */  
    public ?string $TransactionTime;

    /** 
     * Barion identifier of a transaction this one is related to.
     * 
     * @var ?string
     */     
    public ?string $RelatedId;

    function __construct()
    {
        $this->POSTransactionId = "";
        $this->TransactionId = "";
        $this->Status = TransactionStatus::Unknown;
        $this->TransactionTime = "";
        $this->RelatedId = null;
    }

    public function fromJson(array $json) : void
    {
        if (!empty($json)) {
            $this->POSTransactionId = JSON::getString($json, 'POSTransactionId');
            $this->Status = TransactionStatus::from(JSON::getString($json, 'Status') ?? "Unknown");
            $this->TransactionId = JSON::getString($json, 'TransactionId');
            $this->TransactionTime = JSON::getString($json, 'TransactionTime');
            $this->RelatedId = JSON::getString($json, 'RelatedId');
        }
    }
}