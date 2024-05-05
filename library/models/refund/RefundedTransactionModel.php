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

use Barion\Enumerations\{
    TransactionStatus
};

class RefundedTransactionModel implements \Barion\Interfaces\IBarionModel {

    public string $TransactionId;
    public float $Total;
    public ?string $POSTransactionId;
    public ?string $Comment;
    public TransactionStatus $Status;

    function __construct()
    {
        $this->TransactionId = "";
        $this->Total = 0.0;
        $this->POSTransactionId = null;
        $this->Comment = null;
        $this->Status = TransactionStatus::Unknown;
    }


    public function fromJson($json)
    {
        if (!empty($json)) {
            $this->TransactionId = $json['TransactionId'];
            $this->Total = $json['Total'];
            $this->POSTransactionId = $json['POSTransactionId'];
            $this->Comment = $json['Comment'];
            $this->Status = TransactionStatus::from($json['Status']);
        }
    }
}