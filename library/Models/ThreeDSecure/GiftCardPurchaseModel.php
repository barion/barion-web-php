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

namespace Barion\Models\ThreeDSecure;

use Barion\Interfaces\IBarionModel;
use Barion\Helpers\JSON;

/**
 * Model describing a gift card purchase during a 3D-Secure card payment process.
 */
class GiftCardPurchaseModel implements IBarionModel
{
    /** 
     * The total amount of all gift cards that are being purchased during the payment.
     * 
     * @var ?float
     */ 
    public ?float $Amount;

    /** 
     * The number of gift cards being purchased during the payment.
     * 
     * @var ?int
     */ 
    public ?int $Count;

    function __construct()
    {
        $this->Amount = 0.0;
        $this->Count = 0;
    }

    public function fromJson(array $json) : void
    {
        if (!empty($json)) {
            $this->Amount = JSON::getFloat($json, 'Amount');
            $this->Count = JSON::getInt($json, 'Count');
        }
    }
}