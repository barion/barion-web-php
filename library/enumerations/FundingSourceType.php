<?php

namespace Barion\Enumerations;

enum FundingSourceType : string
{
    case All = "All";
    case Balance = "Balance";
    case Bankcard = "Bankcard";
    case BankTransfer = "BankTransfer";
}

?>