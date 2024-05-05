<?php

/**
 * Copyright 2016 Barion Payment Inc. All Rights Reserved.
 * <p/>
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 * <p/>
 * http://www.apache.org/licenses/LICENSE-2.0
 * <p/>
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

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