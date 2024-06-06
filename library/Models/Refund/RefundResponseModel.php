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

use Barion\Interfaces\IBarionModel;
use Barion\Models\BaseResponseModel;
use Barion\Helpers\JSON;

/**
 * Model containing the response data after a refund request sent to the Barion API.
 */
class RefundResponseModel extends BaseResponseModel implements IBarionModel
{    
    /** 
     * The Barion identifier of the payment.
     * 
     * @var ?string
     */
    public ?string $PaymentId;    
    
    /** 
     * Array of transactions that were refunded during the request.
     * 
     * @var array<object> 
     */
    public array $RefundedTransactions;

    function __construct()
    {
        parent::__construct();
        $this->PaymentId = null;
        $this->RefundedTransactions = array();
    }

    public function fromJson(array $json) : void
    {
        if (!empty($json)) {
            parent::fromJson($json);

            $this->PaymentId = JSON::getString($json, 'PaymentId');
            $this->RefundedTransactions = array();

            $refundedTransactions = JSON::getArray($json, 'RefundedTransactions');

            if (!empty($refundedTransactions)) {
                foreach ($refundedTransactions as $key => $refundedTransaction) {
                    $tr = new RefundedTransactionModel();
                    $tr->fromJson($refundedTransaction);
                    $this->RefundedTransactions[] = $tr;
                }
            }
        }
    }
}