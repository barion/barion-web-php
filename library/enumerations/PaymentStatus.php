<?php

namespace Barion\Enumerations;

enum PaymentStatus : string
{
    case Prepared = "Prepared";
    case Started = "Started";
    case InProgress = "InProgress";
    case Waiting = "Waiting";
    case Reserved = "Reserved";
    case Authorized = "Authorized";
    case Canceled = "Canceled";
    case Succeeded = "Succeeded";
    case Failed = "Failed";
    case PartiallySucceeded = "PartiallySucceeded";
    case Expired = "Expired";
}

?>