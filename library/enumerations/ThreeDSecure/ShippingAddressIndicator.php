<?php

namespace Barion\Enumerations\ThreeDSecure;

enum ShippingAddressIndicator : string
{
    case Unspecified = "";
    case ShipToCardholdersBillingAddress = "ShipToCardholdersBillingAddress";
    case ShipToAnotherVerifiedAddress = "ShipToAnotherVerifiedAddress";
    case ShipToDifferentAddress = "ShipToDifferentAddress";
    case ShipToStore = "ShipToStore";
    case DigitalGoods = "DigitalGoods";
    case TravelAndEventTickets = "TravelAndEventTickets";
    case Other = "Other";
}

?>