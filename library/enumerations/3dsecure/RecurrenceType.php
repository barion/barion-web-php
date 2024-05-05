<?php

namespace Barion\Enumerations\ThreeDSecure;

enum RecurrenceType : string
{
    case MerchantInitiatedPayment = "MerchantInitiatedPayment";
    case OneClickPayment = "OneClickPayment";
    case RecurringPayment = "RecurringPayment";
}

?>