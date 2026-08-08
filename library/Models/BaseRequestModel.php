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

namespace Barion\Models;

/**
 * Base model of all requests sent towards the Barion API.
 */
class BaseRequestModel
{
    /** 
     * The secret key of the shop that is calling the Barion API, used for authentication.
     * 
     * @var string
     */   
    public string $POSKey;

    /** 
     * A unique key for the particular request, used to maintain idempotency across subsequent API calls.
     * 
     * @var string
     */   
    private string $IdempotencyKey;

    /** 
     * Add a unique idempotency key to the request
     * 
     * @param string $idempotencyKey
     * @return void
    */
    public function SetIdempotencyKey(string $idempotencyKey) {
        $this->IdempotencyKey = $idempotencyKey;
    }

    /**
     * Gets the idempotency key of the request
     *
     * @return ?string
    */
    public function GetIdempotencyKey(): ?string {
        if (!isset($this->IdempotencyKey)) {
            return null;
        }

        return $this->IdempotencyKey;
    }
}