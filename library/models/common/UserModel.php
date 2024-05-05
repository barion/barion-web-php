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

/**
 * Model containing information about a user partaking in a Barion Smart Gateway payment.
 */
class UserModel implements \Barion\Interfaces\IBarionModel
{
    /** 
     * The name of the user.
     * 
     * @var ?string
     */  
    public ?string $Name;

    /** 
     * The e-mail address of the user.
     * 
     * @var ?string
     */  
    public ?string $Email;

    function __construct()
    {
        $this->Name = null;
        $this->Email = null;
    }

    /** @param array<mixed> $json */
    public function fromJson(array $json) : void
    {
        if ($json !== null && !empty($json)) {
            $this->Email = JSON::getString($json, 'Email');
            $userNameData = JSON::getArray($json, 'Name');
            if ($userNameData !== null) {
                $name = new UserNameModel();
                $name->fromJson($userNameData);
                $this->Name = $name->getName();
            }
        }
    }
}