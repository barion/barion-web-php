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

namespace Barion\Models\Payment;

use Barion\Models\BaseRequestModel;
use Barion\Enumerations\QRCodeSize;

/**
 * @deprecated
 * Model used to request a QR code image for the Barion Smart Gateway URL of a payment.
 */
class PaymentQRRequestModel extends BaseRequestModel
{
    /** 
     * The Barion username (e-mail address) of the caller.
     * 
     * @var string
     */
    public string $UserName;

    /** 
     * The Barion password of the caller.
     * 
     * @var string
     */
    public string $Password;

    /** 
     * The Barion identifier of the payment.
     * 
     * @var string
     */
    public string $PaymentId;

    /** 
     * Size of the requested QR code image.
     * 
     * @var QRCodeSize
     */
    public QRCodeSize $Size;

    function __construct(string $userName, string $password, string $paymentId, QRCodeSize $size = QRCodeSize::Normal)
    {
        $this->UserName = $userName;
        $this->Password = $password;
        $this->PaymentId = $paymentId;
        $this->Size = $size;
    }
}