<?php

namespace Barion\Enumerations;

enum CardType : string
{
    case Unknown = "Unknown";
    case Mastercard = "Mastercard";
    case Maestro = "Maestro";
    case Visa = "Visa";
    case Electron = "Electron";
    case AmericanExpress = "AmericanExpress";
}

?>