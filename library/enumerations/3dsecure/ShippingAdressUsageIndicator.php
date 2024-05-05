<?php

namespace Barion\Enumerations\ThreeDSecure;

abstract class ShippingAddressUsageIndicator
{
    const ThisTransaction = "ThisTransaction";
    const LessThan30Days = "LessThan30Days";
    const Between30And60Days = "Between30And60Days";
    const MoreThan60Days = "MoreThan60Days";
}

?>