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
use Barion\Enumerations\ThreeDSecure\{
    AvailabilityIndicator,
    DeliveryTimeframeType,
    PurchaseType,
    ReOrderIndicator,
    ShippingAddressIndicator
};

/**
 * Model containing information about the purchase during a 3D-Secure card payment process.
 */
class PurchaseInformationModel implements IBarionModel
{
    /** 
     * Indicator about the delivery speed of the purchase.
     * 
     * @var DeliveryTimeframeType
     */ 
    public DeliveryTimeframeType $DeliveryTimeframe;

    /** 
     * The e-mail address attached to the delivery, if applicable.
     * 
     * @var ?string
     */ 
    public ?string $DeliveryEmailAddress;

    /**     
     * ISO-8601 format timestamp when pre-ordered goods will be available, if applicable.
     * 
     * @var ?string
     */ 
    public ?string $PreOrderDate;

    /** 
     * Indicator about the availability of a pre-ordered product, if applicable.
     * 
     * @var AvailabilityIndicator
     */
    public AvailabilityIndicator $AvailabilityIndicator;

    /** 
     * Indicator describing if this purchase is a re-order of a previous purchase.
     * 
     * @var ReOrderIndicator
     */
    public ReOrderIndicator $ReOrderIndicator;

    /**     
     * ISO-8601 format timestamp of the last moment when this purchase can be used a source for a recurring/token payment.
     * 
     * @var ?string
     */ 
    public ?string $RecurringExpiry;

    /**     
     * The minimum number of days between subsequent payments. Only applicable if this is a "recurring payment" scenario.
     * 
     * @var ?int
     */ 
    public ?int $RecurringFrequency;

    /** 
     * Indicator describing the method of shipping.
     * 
     * @var ShippingAddressIndicator
     */
    public ShippingAddressIndicator $ShippingAddressIndicator;

    /** 
     * Model describing the details of a gift card purchase, if applicable.
     * 
     * @var ?object
     */
    public ?object $GiftCardPurchase;

    /** 
     * Indicator describing the type of purchase regarding the nature of goods or services being sold.
     * 
     * @var PurchaseType
     */
    public PurchaseType $PurchaseType;

    /**     
     * ISO-8601 format timestamp of the purchase.
     * 
     * @var ?string
     */ 
    public ?string $PurchaseDate;

    function __construct()
    {
        $this->DeliveryTimeframe = DeliveryTimeframeType::Unspecified;
        $this->DeliveryEmailAddress = null;
        $this->PreOrderDate = null;
        $this->AvailabilityIndicator = AvailabilityIndicator::Unspecified;
        $this->ReOrderIndicator = ReOrderIndicator::Unspecified;
        $this->RecurringExpiry = null;
        $this->RecurringFrequency = null;
        $this->ShippingAddressIndicator = ShippingAddressIndicator::Unspecified;
        $this->GiftCardPurchase = null;
        $this->PurchaseType = PurchaseType::Unspecified;
        $this->PurchaseDate = null;
    }

    public function fromJson(array $json) : void
    {
        if (!empty($json)) {
            $this->DeliveryTimeframe = DeliveryTimeframeType::from(JSON::getString($json, 'DeliveryTimeframe') ?? '');
            $this->DeliveryEmailAddress = JSON::getString($json, 'DeliveryEmailAddress');
            $this->PreOrderDate = JSON::getString($json, 'PreOrderDate');
            $this->AvailabilityIndicator = AvailabilityIndicator::from(JSON::getString($json, 'AvailabilityIndicator') ?? '');
            $this->ReOrderIndicator = ReOrderIndicator::from(JSON::getString($json, 'ReOrderIndicator') ?? '');
            $this->RecurringExpiry = JSON::getString($json, 'RecurringExpiry');
            $this->RecurringFrequency = JSON::getInt($json, 'RecurringFrequency');
            $this->ShippingAddressIndicator = ShippingAddressIndicator::from(JSON::getString($json, 'ShippingAddressIndicator') ?? '');
            $this->GiftCardPurchase = JSON::getObject($json, 'GiftCardPurchase');
            $this->PurchaseType = PurchaseType::from(JSON::getString($json, 'PurchaseType') ?? '');
            $this->PurchaseDate = JSON::getString($json, 'PurchaseDate');
        }
    }
}