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
 
namespace Barion\Models;

use Barion\Helpers\JSON;

use Barion\Models\Error\ApiErrorModel;

class BaseResponseModel
{
    /** @var array<object> */
    public array $Errors;

    public bool $RequestSuccessful;

    function __construct()
    {
        $this->Errors = array();
        $this->RequestSuccessful = false;
    }

    /** @param array<object> $json */
    public function fromJson(array $json) : void
    {
        if (!empty($json)) {
            $this->RequestSuccessful = true;
            if (!array_key_exists('Errors', $json) || !empty(JSON::getArray($json, 'Errors'))) {
                $this->RequestSuccessful = false;
            }

            if (array_key_exists('Errors', $json)) {
                $errors = JSON::getArray($json, 'Errors');
                if (is_array($errors) && !empty($errors)) {
                    foreach ($errors as $key => $error) {
                        $apiError = new ApiErrorModel();
                        $apiError->fromJson($error);
                        array_push($this->Errors, $apiError);
                    }
                }
            } else {
                $internalError = new ApiErrorModel();
                $internalError->ErrorCode = "500";
                if (array_key_exists('ExceptionMessage', $json)) {
                    $internalError->Title = JSON::getString($json, 'ExceptionMessage');
                    $internalError->Description = JSON::getString($json, 'ExceptionType');
                } else {
                    $internalError->Title = "Internal Server Error";
                }

                array_push($this->Errors, $internalError);
            }
        }
    }
}