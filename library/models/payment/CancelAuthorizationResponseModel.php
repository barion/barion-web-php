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

use Barion\Helpers\iBarionModel;
use Barion\Models\BaseResponseModel;

class CancelAuthorizationResponseModel extends BaseResponseModel implements iBarionModel
{
    public $IsSuccessful;
    public $PaymentId;
    public $PaymentRequestId;
    public $Status;
    public $Transactions;

    function __construct()
    {
        parent::__construct();
        $this->IsSuccessful = false;
        $this->PaymentId = "";
        $this->PaymentRequestId = "";
        $this->Status = "";
        $this->Transactions = array();
    }

    public function fromJson($json)
    {
        if (!empty($json)) {
            parent::fromJson($json);

            $this->IsSuccessful = \Barion\Helpers\jget($json, 'IsSuccessful');
            $this->PaymentId = \Barion\Helpers\jget($json, 'PaymentId');
            $this->PaymentRequestId = \Barion\Helpers\jget($json, 'PaymentRequestId');
            $this->Status = \Barion\Helpers\jget($json, 'Status');

            $this->Transactions = array();

            if (!empty($json['Transactions'])) {
                foreach ($json['Transactions'] as $key => $value) {
                    $tr = new TransactionResponseModel();
                    $tr->fromJson($value);
                    array_push($this->Transactions, $tr);
                }
            }
        }
    }
}