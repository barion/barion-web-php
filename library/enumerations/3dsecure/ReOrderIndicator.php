<?php

namespace Barion\Enumerations\ThreeDSecure;

enum ReOrderIndicator : string
{
    case Unspecified = "";
    case FirstTimeOrdered = "FirstTimeOrdered";
    case ReOrdered = "ReOrdered";
}

?>