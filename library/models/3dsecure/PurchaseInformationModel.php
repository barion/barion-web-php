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
class PurchaseInformationModel implements iBarionModel
{
    public $DeliveryTimeframe;
    public $DeliveryEmailAddress;
    public $PreOrderDate;
    public $AvailabilityIndicator;
    public $ReOrderIndicator;
    public $RecurringExpiry;
    public $RecurringFrequency;
    public $ShippingAddressIndicator;
    public $GiftCardPurchase;
    public $PurchaseType;
    public $PurchaseDate;

    function __construct()
    {
        $this->DeliveryTimeframe = "";
        $this->DeliveryEmailAddress = "";
        $this->PreOrderDate = "";
        $this->AvailabilityIndicator = "";
        $this->ReOrderIndicator = "";
        $this->RecurringExpiry = "";
        $this->RecurringFrequency = "";
        $this->ShippingAddressIndicator = "";
        $this->GiftCardPurchase = "";
        $this->PurchaseType = "";
        $this->PurchaseDate = "";
    }

    public function fromJson($json)
    {
        if (!empty($json)) {
            $this->DeliveryTimeframe = jget($json, 'DeliveryTimeframe');
            $this->DeliveryEmailAddress = jget($json, 'DeliveryEmailAddress');
            $this->PreOrderDate = jget($json, 'PreOrderDate');
            $this->AvailabilityIndicator = jget($json, 'AvailabilityIndicator');
            $this->ReOrderIndicator = jget($json, 'ReOrderIndicator');
            $this->RecurringExpiry = jget($json, 'RecurringExpiry');
            $this->RecurringFrequency = jget($json, 'RecurringFrequency');
            $this->ShippingAddressIndicator = jget($json, 'ShippingAddressIndicator');
            $this->GiftCardPurchase = jget($json, 'GiftCardPurchase');
            $this->PurchaseType = jget($json, 'PurchaseType');
            $this->PurchaseDate = jget($json, 'PurchaseDate');
        }
    }
}