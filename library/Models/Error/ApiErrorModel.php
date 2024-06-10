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
 
namespace Barion\Models\Error;

use Barion\Interfaces\IBarionModel;
use Barion\Helpers\JSON;

/**
 * Model containing error response data received from the Barion API.
 */
class ApiErrorModel implements IBarionModel
{
    /** 
     * The title of the error message.
     * 
     * @var ?string
     */  
    public ?string $Title;

    /** 
     * An alphanumeric code of the error.
     * 
     * @var ?string
     */  
    public ?string $ErrorCode;

    /** 
     * The detailed description of the error, if applicable.
     * 
     * @var ?string
     */  
    public ?string $Description;

    /** 
     * The timestamp when the error occured.
     * 
     * @var ?string
     */  
    public ?string $HappenedAt;

    /** 
     * Details about the authentication data available during the request where the error occured.
     * 
     * @var ?string
     */  
    public ?string $AuthData;

    /** 
     * The API endpoint on which the error occured.
     * 
     * @var ?string
     */  
    public ?string $EndPoint;

    /** 
     * The Barion payment identifier related to the error, if applicable.
     * 
     * @var ?string
     */  
    public ?string $PaymentId;

    function __construct()
    {
        $this->Title = null;
        $this->ErrorCode = null;
        $this->Description = null;
        $this->HappenedAt = null;
        $this->AuthData = null;
        $this->EndPoint = null;
        $this->PaymentId = null;
    }

    /**
     * Build model from a JSON array.
     *  
     * @param array<object> $json 
     * @return void
    */
    public function fromJson(array $json) : void
    {
        if (!empty($json)) {
            $this->ErrorCode = JSON::getString($json, 'ErrorCode');
            $this->Title = JSON::getString($json, 'Title');
            $this->Description = JSON::getString($json, 'Description');
            $this->HappenedAt = JSON::getString($json, 'HappenedAt');
            $this->AuthData = JSON::getString($json, 'AuthData');
            $this->EndPoint = JSON::getString($json, 'EndPoint');
            
            if (array_key_exists('PaymentId', $json)) {
                $this->PaymentId = JSON::getString($json, 'PaymentId');
            }
        }
    }
}