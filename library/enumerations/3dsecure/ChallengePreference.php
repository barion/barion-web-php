<?php

namespace Barion\Enumerations\ThreeDSecure;

enum ChallengePreference : string
{
    case Unspecified = "";
    case NoPreference = "NoPreference";
    case ChallengeRequired = "ChallengeRequired";
    case NoChallengeNeeded = "NoChallengeNeeded";
}

?>