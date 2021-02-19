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
class BankAccountModel implements iBarionModel
{
    public $Country;
    public $Format;
    public $AccountNumber;
    public $Address;
    public $BankName;
    public $BankAddress;
    public $SwiftCode;

    function __construct()
    {
        $this->Country = "";
        $this->Format = "";
        $this->AccountNumber = "";
        $this->Address = "";
        $this->BankName = "";
        $this->BankAddress = "";
    }

    public function fromJson($json)
    {
        if (!empty($json)) {
            $this->Country = jget($json, 'Country');
            $this->Format = jget($json, 'Format');
            $this->AccountNumber = jget($json, 'AccountNumber');
            $this->Address = jget($json, 'Address');
            $this->BankName = jget($json, 'BankName');
            $this->BankAddress = jget($json, 'BankAddress');
            $this->SwiftCode = jget($json, 'SwiftCode');
        }
    }
}
