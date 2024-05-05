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

namespace Barion\Models\Payment;

class PaymentQRRequestModel extends \Barion\Models\BaseRequestModel
{
    public string $UserName;
    public string $Password;
    public string $PaymentId;
    public string $Size;

    function __construct($userName, $password, $paymentId)
    {
        $this->UserName = $userName;
        $this->Password = $password;
        $this->PaymentId = $paymentId;
        $this->Size = QRCodeSize::Normal;
    }
}