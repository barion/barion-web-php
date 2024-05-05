<?php

namespace Barion\Enumerations;

enum RecurrenceResult : string
{
    case None = "None";
    case Successful = "Successful";
    case Failed = "Failed";
    case NotFound = "NotFound";
}

?>