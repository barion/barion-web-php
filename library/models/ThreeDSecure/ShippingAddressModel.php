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

namespace Barion\Models\ThreeDSecure;

use Barion\Helpers\JSON;

class ShippingAddressModel implements \Barion\Interfaces\IBarionModel
{
    public ?string $Country;
    public ?string  $Region;
    public ?string $City;
    public ?string $Zip;
    public ?string $Street;
    public ?string $Street2;
    public ?string $Street3;
    public ?string $FullName;

    function __construct()
    {
        $this->Country = null;
        $this->Region = null;
        $this->City = null;
        $this->Zip = null;
        $this->Street = null;
        $this->Street2 = null;
        $this->Street3 = null;
        $this->FullName = null;
    }

    public function fromJson(array $json) : void
    {
        if (!empty($json)) {
            $this->Country = JSON::getString($json, 'Country');
            $this->Region = JSON::getString($json, 'Region');
            $this->City = JSON::getString($json, 'City');
            $this->Zip = JSON::getString($json, 'Zip');
            $this->Street = JSON::getString($json, 'Street');
            $this->Street2 = JSON::getString($json, 'Street2');
            $this->Street3 = JSON::getString($json, 'Street3');
            $this->FullName = JSON::getString($json, 'FullName');
        }
    }
}