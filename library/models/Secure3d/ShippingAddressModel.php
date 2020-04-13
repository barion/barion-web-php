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

namespace Barion\Models\Secure3d;

use Barion\Helpers\iBarionModel;

class ShippingAddressModel implements iBarionModel
{
    public $Country;
    public $Region;
    public $City;
    public $Zip;
    public $Street;
    public $Street2;
    public $Street3;
    public $FullName;

    function __construct()
    {
        $this->Country = "";
        $this->Region = "";
        $this->City = "";
        $this->Zip = "";
        $this->Street = "";
        $this->Street2 = "";
        $this->Street3 = "";
        $this->FullName = "";
    }

    public function fromJson($json)
    {
        if (!empty($json)) {
            $this->Country = \Barion\Helpers\jget($json, 'Country');
            $this->Region = \Barion\Helpers\jget($json, 'Region');
            $this->City = \Barion\Helpers\jget($json, 'City');
            $this->Zip = \Barion\Helpers\jget($json, 'Zip');
            $this->Street = \Barion\Helpers\jget($json, 'Street');
            $this->Street2 = \Barion\Helpers\jget($json, 'Street2');
            $this->Street3 = \Barion\Helpers\jget($json, 'Street3');
            $this->FullName = \Barion\Helpers\jget($json, 'FullName');
        }
    }
}