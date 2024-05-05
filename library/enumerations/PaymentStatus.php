<?php

namespace Barion\Enumerations;

enum PaymentStatus : string
{
    // 10
    case Prepared = "Prepared";
    // 20
    case Started = "Started";
    // 21
    case InProgress = "InProgress";
    // 22
    case Waiting = "Waiting";
    // 25
    case Reserved = "Reserved";
    // 26
    case Authorized = "Authorized";
    // 30
    case Canceled = "Canceled";
    // 40
    case Succeeded = "Succeeded";
    // 50
    case Failed = "Failed";
    // 60
    case PartiallySucceeded = "PartiallySucceeded";
    // 70
    case Expired = "Expired";
}

?>