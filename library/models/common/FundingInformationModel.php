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

namespace Barion\Models\Common;

use Barion\Helpers\JSON;

class FundingInformationModel implements \Barion\Interfaces\IBarionModel
{
    public object $BankCard;
    public ?string $AuthorizationCode;
    public ?string $ProcessResult;

    function __construct()
    {
        $this->BankCard = new BankCardModel();
        $this->AuthorizationCode = null;
        $this->ProcessResult = null;
    }

    /** @param array<object> $json */
    public function fromJson(array $json) : void
    {
        if (!empty($json)) {
            $this->BankCard = new BankCardModel();
            $this->BankCard->fromJson(JSON::getArray($json, 'BankCard') ?? array());
            $this->AuthorizationCode = JSON::getString($json, 'AuthorizationCode');
            $this->ProcessResult = JSON::getString($json, 'ProcessResult');
        }
    }
}