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
class TransactionResponseModel implements iBarionModel
{
    public $POSTransactionId;
    public $TransactionId;
    public $Status;
    public $TransactionTime;
    public $RelatedId;

    function __construct()
    {
        $this->POSTransactionId = "";
        $this->TransactionId = "";
        $this->Status = "";
        $this->TransactionTime = "";
        $this->RelatedId = "";
    }

    public function fromJson($json)
    {
        if (!empty($json)) {
            $this->POSTransactionId = $json['POSTransactionId'];
            $this->Status = $json['Status'];
            $this->TransactionId = $json['TransactionId'];
            $this->TransactionTime = $json['TransactionTime'];
            $this->RelatedId = $json['RelatedId'];
        }
    }
}