<?php

abstract class BarionEnvironment
{
    const Test = "test";
    const Prod = "prod";
}

abstract class PaymentType
{
    const Immediate = "Immediate";
    const Reservation = "Reservation";
}

abstract class FundingSourceType
{
    const All = "All";
    const Balance = "Balance";
    const Bankcard = "Bankcard";
}

abstract class PaymentStatus
{
    // 10
    const Prepared = "Prepared";
    // 20
    const Started = "Started";
    // 21
    const InProgress = "InProgress";
    // 25
    const Reserved = "Reserved";
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

abstract class QRCodeSize
{
    const Small = "Small";
    const Normal = "Normal";
    const Large = "Large";
}

abstract class RecurrenceResult
{
    const None = "None";
    const Successful = "Successful";
    const Failed = "Failed";
    const NotFound = "NotFound";
}

abstract class UILocale
{
    const HU = "hu-HU";
    const EN = "en-US";
    const DE = "de-DE";
    const SL = "sl-SL";
    const SK = "sk-SK";
    const FR = "fr-FR";
    
}

abstract class Currency
{
    const HUF = "HUF";
    const EUR = "EUR";
    const USD = "USD";

    public static function isValid($name)
    {
        $class = new ReflectionClass(__CLASS__);
        $constants = $class->getConstants();
        return array_key_exists($name, $constants);
    }
}

abstract class CardType
{
    const Unknown = "Unknown";
    const Mastercard = "Mastercard";
    const Maestro = "Maestro";
    const Visa = "Visa";
    const Electron = "Electron";
    const AmericanExpress = "AmericanExpress";
}