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
 
namespace Barion;

$include_dirs = Array(
    realpath(join(DIRECTORY_SEPARATOR, array(dirname(__FILE__), "constants"))),
    realpath(join(DIRECTORY_SEPARATOR, array(dirname(__FILE__), "interfaces"))),
    realpath(join(DIRECTORY_SEPARATOR, array(dirname(__FILE__), "enumerations"))),
    realpath(join(DIRECTORY_SEPARATOR, array(dirname(__FILE__), "enumerations/3dsecure"))),
    realpath(join(DIRECTORY_SEPARATOR, array(dirname(__FILE__), "helpers"))),
    realpath(join(DIRECTORY_SEPARATOR, array(dirname(__FILE__), "models"))),
    realpath(join(DIRECTORY_SEPARATOR, array(dirname(__FILE__), "models/common"))),
    realpath(join(DIRECTORY_SEPARATOR, array(dirname(__FILE__), "models/error"))),
    realpath(join(DIRECTORY_SEPARATOR, array(dirname(__FILE__), "models/3dsecure"))),
    realpath(join(DIRECTORY_SEPARATOR, array(dirname(__FILE__), "models/payment"))),
    realpath(join(DIRECTORY_SEPARATOR, array(dirname(__FILE__), "models/refund")))
);

foreach ($include_dirs as $directoryKey => $directoryName) {
    $files = glob($directoryName . '/*.php');   
    foreach ($files as $fileKey => $fileName) {
        require_once $fileName;
    }
}