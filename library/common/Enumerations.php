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
    const DelayedCapture = "DelayedCapture";
}

abstract class FundingSourceType
{
    const All = "All";
    const Balance = "Balance";
    const Bankcard = "Bankcard";
    const BankTransfer = "BankTransfer";
}

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
    const SL = "sl-SI";
    const SK = "sk-SK";
    const FR = "fr-FR";
    const CZ = "cs-CZ";
    const GR = "el-GR";
}

abstract class Currency
{
    const HUF = "HUF";
    const EUR = "EUR";
    const USD = "USD";
    const CZK = "CZK";

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


// --------------------
// 3D Secure properties
// --------------------

abstract class AccountCreationIndicator
{
    const NoAccount = "NoAccount";
    const CreatedDuringThisTransaction = "CreatedDuringThisTransaction";
    const LessThan30Days = "LessThan30Days";
    const Between30And60Days = "Between30And60Days";
    const MoreThan60Days = "MoreThan60Days";
}

abstract class AccountChangeIndicator
{
    const ChangedDuringThisTransaction = "ChangedDuringThisTransaction";
    const LessThan30Days = "LessThan30Days";
    const Between30And60Days = "Between30And60Days";
    const MoreThan60Days = "MoreThan60Days";
}

abstract class PasswordChangeIndicator
{
    const NoChange = "NoChange";
    const ChangedDuringThisTransaction = "ChangedDuringThisTransaction";
    const LessThan30Days = "LessThan30Days";
    const Between30And60Days = "Between30And60Days";
    const MoreThan60Days = "MoreThan60Days";
}

abstract class ShippingAddressUsageIndicator
{
    const ThisTransaction = "ThisTransaction";
    const LessThan30Days = "LessThan30Days";
    const Between30And60Days = "Between30And60Days";
    const MoreThan60Days = "MoreThan60Days";
}

abstract class PaymentMethodIndicator
{
    const NoAccount = "NoAccount";
    const ThisTransaction = "ThisTransaction";
    const LessThan30Days = "LessThan30Days";
    const Between30And60Days = "Between30And60Days";
    const MoreThan60Days = "MoreThan60Days";
}

abstract class SuspiciousActivityIndicator
{
    const NoSuspiciousActivityObserved = "NoSuspiciousActivityObserved";
    const SuspiciousActivityObserved = "SuspiciousActivityObserved";
}

abstract class DeliveryTimeframeType
{
    const ElectronicDelivery = "ElectronicDelivery";
    const SameDayShipping = "SameDayShipping";
    const OvernightShipping = "OvernightShipping";
    const TwoDayOrMoreShipping = "TwoDayOrMoreShipping";
}

abstract class AvailabilityIndicator
{
    const MerchandiseAvailable = "MerchandiseAvailable";
    const FutureAvailability = "FutureAvailability";
}

abstract class ReOrderIndicator
{
    const FirstTimeOrdered = "FirstTimeOrdered";
    const ReOrdered = "ReOrdered";
}

abstract class ShippingAddressIndicator
{
    const ShipToCardholdersBillingAddress = "ShipToCardholdersBillingAddress";
    const ShipToAnotherVerifiedAddress = "ShipToAnotherVerifiedAddress";
    const ShipToDifferentAddress = "ShipToDifferentAddress";
    const ShipToStore = "ShipToStore";
    const DigitalGoods = "DigitalGoods";
    const TravelAndEventTickets = "TravelAndEventTickets";
    const Other = "Other";
}

abstract class PurchaseType
{
    const GoodsAndServicePurchase = "GoodsAndServicePurchase";
    const CheckAcceptance = "CheckAcceptance";
    const AccountFunding = "AccountFunding";
    const QuasiCashTransaction = "QuasiCashTransaction";
    const PrePaidVacationAndLoan = "PrePaidVacationAndLoan";
}

abstract class RecurrenceType 
{
    const MerchantInitiatedPayment = "MerchantInitiatedPayment";
    const OneClickPayment = "OneClickPayment";
    const RecurringPayment = "RecurringPayment";
}

abstract class ChallengePreference 
{
    const NoPreference = "NoPreference";
    const ChallengeRequired = "ChallengeRequired";
    const NoChallengeNeeded = "NoChallengeNeeded";
}