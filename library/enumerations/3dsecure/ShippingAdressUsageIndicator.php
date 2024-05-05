<?php

namespace Barion\Enumerations\ThreeDSecure;

enum ShippingAddressUsageIndicator : string
{
    case ThisTransaction = "ThisTransaction";
    case LessThan30Days = "LessThan30Days";
    case Between30And60Days = "Between30And60Days";
    case MoreThan60Days = "MoreThan60Days";
}

?>