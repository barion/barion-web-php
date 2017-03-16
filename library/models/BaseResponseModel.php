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
class BaseResponseModel
{
    public $Errors;
    public $RequestSuccessful;

    function __construct()
    {
        $this->Errors = array();
        $this->RequestSuccessful = false;
    }

    public function fromJson($json)
    {
        if (!empty($json)) {
            $this->RequestSuccessful = true;
            if (!array_key_exists('Errors', $json) || !empty($json['Errors'])) {
                $this->RequestSuccessful = false;
            }

            if (array_key_exists('Errors', $json)) {
                foreach ($json['Errors'] as $error) {
                    $apiError = new ApiErrorModel();
                    $apiError->fromJson($error);
                    array_push($this->Errors, $apiError);
                }
            } else {
                $internalError = new ApiErrorModel();
                $internalError->ErrorCode = "500";
                if (array_key_exists('ExceptionMessage', $json)) {
                    $internalError->Title = $json['ExceptionMessage'];
                    $internalError->Description = $json['ExceptionType'];
                } else {
                    $internalError->Title = "Internal Server Error";
                }

                array_push($this->Errors, $internalError);
            }
        }
    }
}