<?php

namespace Barion\Enumerations;

enum Currency : string
{
    case Unspecified = "Unspecified";
    case HUF = "HUF";
    case EUR = "EUR";
    case USD = "USD";
    case CZK = "CZK";
}

?>