<?php

namespace Barion\Enumerations\ThreeDSecure;

abstract class ShippingAddressIndicator
{
    const ShipToCardholdersBillingAddress = "ShipToCardholdersBillingAddress";
    const ShipToAnotherVerifiedAddress = "ShipToAnotherVerifiedAddress";
    const ShipToDifferentAddress = "ShipToDifferentAddress";
    const ShipToStore = "ShipToStore";
    const DigitalGoods = "DigitalGoods";
    const TravelAndEventTickets = "TravelAndEventTickets";
    const Other = "Other";
}

?>