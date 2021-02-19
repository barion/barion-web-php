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
class BankTransferResponseModel extends BaseResponseModel implements iBarionModel
{
    public $TransactionId;
    public $Currency;
    public $Amount;
    public $RecipientName;
    public $Comment;
    public $BankAccount;

    function __construct()
    {
        parent::__construct();
        $this->TransactionId = "";
        $this->Currency = "EUR";
        $this->Amount = 0;
        $this->RecipientName = "";
        $this->Comment = "";
        $this->BankAccount = new BankAccountModel();
    }

    public function fromJson($json)
    {
        if (!empty($json)) {
            parent::fromJson($json);
            $this->TransactionId = jget($json, 'TransactionId');
            $this->Currency = jget($json, 'Currency');
            $this->Amount = jget($json, 'Amount');
            $this->RecipientName = jget($json, 'RecipientName');
            $this->Comment = jget($json, 'Comment');
            $this->ErrorCode = jget($json, 'ErrorCode');
            $this->BankAccount = new BankAccountModel();
            $this->BankAccount->fromJson(jget($json, 'BankAccount'));
        }
    }
}