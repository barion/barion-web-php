<?php

abstract class BarionEnvironment {
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
    // 30
    const Reserved = "Reserved";
    // 40
    const Canceled = "Canceled";
    // 50
    const Succeeded = "Succeeded";
    // 60
    const Failed = "Failed";
}

abstract class QRCodeSize {
    const Small = "Small";
    const Normal = "Normal";
    const Large = "Large";
}

abstract class RecurrenceResult {
    const None = "None";
    const Successful = "Successful";
    const Failed = "Failed";
    const NotFound = "NotFound";
}

abstract class UILocale {
    const HU = "hu-HU";
    const EN = "en-US";
}

?>