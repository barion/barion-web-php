<?php

namespace Barion\Enumerations\ThreeDSecure;

enum PurchaseType : string
{
    case Unspecified = "";
    case GoodsAndServicePurchase = "GoodsAndServicePurchase";
    case CheckAcceptance = "CheckAcceptance";
    case AccountFunding = "AccountFunding";
    case QuasiCashTransaction = "QuasiCashTransaction";
    case PrePaidVacationAndLoan = "PrePaidVacationAndLoan";
}

?>