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
            $this->Name = jget($json, 'Name');
            $this->Description = jget($json, 'Description');
            $this->Quantity = jget($json, 'Quantity');
            $this->Unit = jget($json, 'Unit');
            $this->UnitPrice = jget($json, 'UnitPrice');
            $this->ItemTotal = jget($json, 'ItemTotal');
            $this->SKU = jget($json, 'SKU');
        }
    }
}