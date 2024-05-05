<?php

namespace Barion\Enumerations\ThreeDSecure;

enum PasswordChangeIndicator : string
{
    case Unspecified = "";
    case NoChange = "NoChange";
    case ChangedDuringThisTransaction = "ChangedDuringThisTransaction";
    case LessThan30Days = "LessThan30Days";
    case Between30And60Days = "Between30And60Days";
    case MoreThan60Days = "MoreThan60Days";
}

?>