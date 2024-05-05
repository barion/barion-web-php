<?php

namespace Barion\Enumerations\ThreeDSecure;

enum RecurrenceType : string
{
    case Unspecified = "";
    case MerchantInitiatedPayment = "MerchantInitiatedPayment";
    case OneClickPayment = "OneClickPayment";
    case RecurringPayment = "RecurringPayment";
}

?>