<?php

namespace Barion\Enumerations\ThreeDSecure;

enum AvailabilityIndicator : string
{
    case Unspecified = "";
    case MerchandiseAvailable = "MerchandiseAvailable";
    case FutureAvailability = "FutureAvailability";
}

?>