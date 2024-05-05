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

namespace Barion\Enumerations;

enum TransactionStatus : string 
{
    case Prepared = "Prepared";
    case Started = "Started";
    case Succeeded = "Succeeded";
    case Timeout = "Timeout";
    case ShopIsDeleted = "ShopIsDeleted";
    case ShopIsClosed = "ShopIsClosed";
    case Rejected = "Rejected";
    case RejectedByShop = "RejectedByShop";
    case Storno = "Storno";
    case Reserved = "Reserved";
    case Deleted = "Deleted";
    case Expired = "Expired";
    case Authorized = "Authorized";
    case Reversed = "Reversed";
    case InvalidPaymentRecord = "InvalidPaymentRecord";
    case PaymentTimeOut = "PaymentTimeOut";
    case InvalidPaymentStatus = "InvalidPaymentStatus";
    case PaymentSenderOrRecipientIsInvalid = "PaymentSenderOrRecipientIsInvalid";
    case Unknown = "Unknown";
}