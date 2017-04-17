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
class ApiErrorModel
{
    public $ErrorCode;
    public $Title;
    public $Description;

    function __construct()
    {
        $this->ErrorCode = "";
        $this->Title = "";
        $this->Description = "";
    }

    public function fromJson($json)
    {
        if (!empty($json)) {
            $this->ErrorCode = $json['ErrorCode'];
            $this->Title = $json['Title'];
            $this->Description = $json['Description'];
        }
    }
}