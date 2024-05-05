<?php

namespace Barion\Enumerations;

enum Currency : string
{
    case HUF = "HUF";
    case EUR = "EUR";
    case USD = "USD";
    case CZK = "CZK";

    public static function isValid($name)
    {
        $class = new ReflectionClass(__CLASS__);
        $constants = $class->getConstants();
        return array_key_exists($name, $constants);
    }
}

?>