<?php

namespace Barion\Enumerations;

abstract class PaymentType
{
    const Immediate = "Immediate";
    const Reservation = "Reservation";
    const DelayedCapture = "DelayedCapture";
}

?>