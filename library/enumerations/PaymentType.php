<?php

namespace Barion\Enumerations;

enum PaymentType : string
{
    case Immediate = "Immediate";
    case Reservation = "Reservation";
    case DelayedCapture = "DelayedCapture";
}

?>