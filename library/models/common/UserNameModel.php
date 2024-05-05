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

namespace Barion\Models\Common;

use Barion\Helpers\JSON;
use Barion\Helpers\StringExtension;

/**
 * Model containing information about the name of a user partaking in a Barion Smart Gateway payment.
 */
class UserNameModel implements \Barion\Interfaces\IBarionModel
{
    /** 
     * The Barion login name of the user.
     * 
     * @var ?string
     */  
    public ?string $LoginName;

    /** 
     * The first name of the user. Applicable only for individuals.
     * 
     * @var ?string
     */  
    public ?string $FirstName;

    /** 
     * The last name of the user. Applicable only for individuals.
     * 
     * @var ?string
     */  
    public ?string $LastName;

    /** 
     * The full organization name of the user. Applicable only for organizations.
     * 
     * @var ?string
     */  
    public ?string $OrganizationName;

    function __construct()
    {
        $this->LoginName = null;
        $this->FirstName = null;
        $this->LastName = null;
        $this->OrganizationName = null;
    }

    /** @param array<mixed> $json */
    public function fromJson(array $json) : void
    {
        if (!empty($json)) {
            $this->LoginName = JSON::getString($json, 'LoginName');
            $this->FirstName = JSON::getString($json, 'FirstName');
            $this->LastName = JSON::getString($json, 'LastName');
            $this->OrganizationName = JSON::getString($json, 'OrganizationName');
        }
    }
    
    public function getName() : string
    {
        if (!StringExtension::isNullOrEmpty($this->OrganizationName)) {
            return strval($this->OrganizationName);
        }
        
        if (!StringExtension::isNullOrEmpty($this->FirstName) || !StringExtension::isNullOrEmpty($this->LastName)) {
            return trim(strval($this->FirstName) . " " . strval($this->LastName));
        }
        
        if (!StringExtension::isNullOrEmpty($this->LoginName)) {
            return strval($this->LoginName);
        }
        
        return "";
    }
}