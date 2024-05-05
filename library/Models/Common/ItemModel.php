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

use Barion\Interfaces\IBarionModel;
use Barion\Helpers\JSON;

/**
 * Model representing an item included in a payment transaction.
 */
class ItemModel implements IBarionModel
{
    /** 
     * The name of the item.
     * 
     * @var ?string
     */  
    public ?string $Name;

    /** 
     * The decription of the item.
     * 
     * @var ?string
     */  
    public ?string $Description;
    
    /** 
     * The quantity purchased during the transaction.
     * 
     * @var ?float
     */  
    public ?float $Quantity;
    
    /** 
     * The unit name, if applicable.
     * Example: piece, month, kilograms, etc.
     * 
     * @var ?string
     */  
    public ?string $Unit;
    
    /** 
     * Price of one unit of the item.
     * 
     * @var ?float
     */  
    public ?float $UnitPrice;
    
    /** 
     * The total amount paid for the item.
     * 
     * @var ?float
     */  
    public ?float $ItemTotal;
    
    /** 
     * Storage Keeping Unit, an internal identifier specified by the shop.
     * 
     * @var ?string
     */  
    public ?string $SKU;

    function __construct()
    {
        $this->Name = null;
        $this->Description = null;
        $this->Quantity = null;
        $this->Unit = null;
        $this->UnitPrice = null;
        $this->ItemTotal = null;
        $this->SKU = null;
    }

    public function fromJson(array $json) : void
    {
        if (!empty($json)) {
            $this->Name = JSON::getString($json, 'Name');
            $this->Description = JSON::getString($json, 'Description');
            $this->Quantity = JSON::getFloat($json, 'Quantity');
            $this->Unit = JSON::getString($json, 'Unit');
            $this->UnitPrice = JSON::getFloat($json, 'UnitPrice');
            $this->ItemTotal = JSON::getFloat($json, 'ItemTotal');
            $this->SKU = JSON::getString($json, 'SKU');
        }
    }
}