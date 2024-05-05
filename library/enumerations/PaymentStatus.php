<?php

namespace Barion\Enumerations;

abstract class PaymentStatus
{
    // 10
    const Prepared = "Prepared";
    // 20
    const Started = "Started";
    // 21
    const InProgress = "InProgress";
    // 22
    const Waiting = "Waiting";
    // 25
    const Reserved = "Reserved";
    // 26
    const Authorized = "Authorized";
    // 30
    const Canceled = "Canceled";
    // 40
    const Succeeded = "Succeeded";
    // 50
    const Failed = "Failed";
    // 60
    const PartiallySucceeded = "PartiallySucceeded";
    // 70
    const Expired = "Expired";
}

?>