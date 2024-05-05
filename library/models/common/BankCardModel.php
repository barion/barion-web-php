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

class BankCardModel implements \Barion\Interfaces\IBarionModel
{
    public ?string $MaskedPan;
    public ?string $BankCardType;
    public ?string $ValidThruYear;
    public ?string $ValidThruMonth;

    function __construct()
    {
        $this->MaskedPan = "";
        $this->BankCardType = "";
        $this->ValidThruYear = "";
        $this->ValidThruMonth = "";
    }

    public function fromJson(array $json) : void
    {
        if (!empty($json)) {
            $this->MaskedPan = JSON::getString($json, 'MaskedPan');
            $this->BankCardType = JSON::getString($json, 'BankCardType');
            $this->ValidThruYear = JSON::getString($json, 'ValidThruYear');
            $this->ValidThruMonth = JSON::getString($json, 'ValidThruMonth');
        }
    }
}