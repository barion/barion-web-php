<?php

namespace Barion\Enumerations\ThreeDSecure;

enum SuspiciousActivityIndicator : string
{
    case NoSuspiciousActivityObserved = "NoSuspiciousActivityObserved";
    case SuspiciousActivityObserved = "SuspiciousActivityObserved";
}

?>