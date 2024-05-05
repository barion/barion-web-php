<?php

namespace Barion\Enumerations\ThreeDSecure;

enum ChallengePreference : string
{
    case NoPreference = "NoPreference";
    case ChallengeRequired = "ChallengeRequired";
    case NoChallengeNeeded = "NoChallengeNeeded";
}

?>