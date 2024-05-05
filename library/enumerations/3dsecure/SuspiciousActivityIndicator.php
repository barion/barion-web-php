<?php

namespace Barion\Enumerations\ThreeDSecure;

enum SuspiciousActivityIndicator : string
{
    case Unspecified = "";
    case NoSuspiciousActivityObserved = "NoSuspiciousActivityObserved";
    case SuspiciousActivityObserved = "SuspiciousActivityObserved";
}

?>