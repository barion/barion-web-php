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

use Barion\Helpers\iBarionModel;

class ItemModel implements iBarionModel
{
    public $Name;
    public $Description;
    public $Quantity;
    public $Unit;
    public $UnitPrice;
    public $ItemTotal;
    public $SKU;

    function __construct()
    {
        $this->Name = "";
        $this->Description = "";
        $this->Quantity = 0;
        $this->Unit = "";
        $this->UnitPrice = 0;
        $this->ItemTotal = 0;
        $this->SKU = "";
    }

    public function fromJson($json)
    {
        if (!empty($json)) {
            $this->Name = \Barion\Helpers\jget($json, 'Name');
            $this->Description = \Barion\Helpers\jget($json, 'Description');
            $this->Quantity = \Barion\Helpers\jget($json, 'Quantity');
            $this->Unit = \Barion\Helpers\jget($json, 'Unit');
            $this->UnitPrice = \Barion\Helpers\jget($json, 'UnitPrice');
            $this->ItemTotal = \Barion\Helpers\jget($json, 'ItemTotal');
            $this->SKU = \Barion\Helpers\jget($json, 'SKU');
        }
    }
}