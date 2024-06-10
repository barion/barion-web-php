<?php

/**
 * Copyright 2024 Barion Payment Inc. All Rights Reserved.
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
 
namespace Barion;

/*
* Autoloader for the Barion library.
*/

use DirectoryIterator;

$include_dirs = Array(
    realpath(join(DIRECTORY_SEPARATOR, array(dirname(__FILE__), "Interfaces"))),
    realpath(join(DIRECTORY_SEPARATOR, array(dirname(__FILE__), "Exceptions"))),
    realpath(join(DIRECTORY_SEPARATOR, array(dirname(__FILE__), "Enumerations"))),
    realpath(join(DIRECTORY_SEPARATOR, array(dirname(__FILE__), "Enumerations/ThreeDSecure"))),
    realpath(join(DIRECTORY_SEPARATOR, array(dirname(__FILE__), "Helpers"))),
    realpath(join(DIRECTORY_SEPARATOR, array(dirname(__FILE__), "Models"))),
    realpath(join(DIRECTORY_SEPARATOR, array(dirname(__FILE__), "Models/Common"))),
    realpath(join(DIRECTORY_SEPARATOR, array(dirname(__FILE__), "Models/Error"))),
    realpath(join(DIRECTORY_SEPARATOR, array(dirname(__FILE__), "Models/ThreeDSecure"))),
    realpath(join(DIRECTORY_SEPARATOR, array(dirname(__FILE__), "Models/Payment"))),
    realpath(join(DIRECTORY_SEPARATOR, array(dirname(__FILE__), "Models/Refund")))
);

foreach ($include_dirs as $directoryKey => $directoryName) {
    $files = new DirectoryIterator(strval($directoryName));
    foreach ($files as $fileInfo) {
        if (!$fileInfo->isDot() && !$fileInfo->isDir()) {
            $filePath = $directoryName . DIRECTORY_SEPARATOR . $fileInfo->getFilename();
            require_once $filePath;
        }
    }
}