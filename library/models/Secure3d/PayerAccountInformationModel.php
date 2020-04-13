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

namespace Barion\Models\Secure3d;

use Barion\Helpers\iBarionModel;

class PayerAccountInformationModel implements iBarionModel
{
    public $AccountId;
    public $AccountCreated;
    public $AccountCreationIndicator;
    public $AccountLastChanged;
    public $AccountChangeIndicator;
    public $PasswordLastChanged;
    public $PasswordChangeIndicator;
    public $PurchasesInTheLastSixMonths;
    public $ShippingAddressAdded;
    public $ShippingAddressUsageIndicator;
    public $PaymentMethodAdded;
    public $PaymentMethodIndicator;
    public $ProvisionAttempts;
    public $TransactionalActivityPerDay;
    public $TransactionalActivityPerYear;
    public $SuspiciousActivityIndicator;

    function __construct()
    {
        $this->AccountId = "";
        $this->AccountCreated = "";
        $this->AccountCreationIndicator = "";
        $this->AccountLastChanged = "";
        $this->AccountChangeIndicator = "";
        $this->PasswordLastChanged = "";
        $this->PasswordChangeIndicator = "";
        $this->PurchasesInTheLastSixMonths = "";
        $this->ShippingAddressAdded = "";
        $this->ShippingAddressUsageIndicator = "";
        $this->PaymentMethodAdded = "";
        $this->PaymentMethodIndicator = "";
        $this->ProvisionAttempts = "";
        $this->TransactionalActivityPerDay = "";
        $this->TransactionalActivityPerYear = "";
        $this->SuspiciousActivityIndicator = "";
    }

    public function fromJson($json)
    {
        if (!empty($json)) {
            $this->AccountId = \Barion\Helpers\jget($json, 'AccountId');
            $this->AccountCreated = \Barion\Helpers\jget($json, 'AccountCreated');
            $this->AccountCreationIndicator = \Barion\Helpers\jget($json, 'AccountCreationIndicator');
            $this->AccountLastChanged = \Barion\Helpers\jget($json, 'AccountLastChanged');
            $this->AccountChangeIndicator = \Barion\Helpers\jget($json, 'AccountChangeIndicator');
            $this->PasswordLastChanged = \Barion\Helpers\jget($json, 'PasswordLastChanged');
            $this->PasswordChangeIndicator = \Barion\Helpers\jget($json, 'PasswordChangeIndicator');
            $this->PurchasesInTheLastSixMonths = \Barion\Helpers\jget($json, 'PurchasesInTheLastSixMonths');
            $this->ShippingAddressAdded = \Barion\Helpers\jget($json, 'ShippingAddressAdded');
            $this->ShippingAddressUsageIndicator = \Barion\Helpers\jget($json, 'ShippingAddressUsageIndicator');
            $this->PaymentMethodAdded = \Barion\Helpers\jget($json, 'PaymentMethodAdded');
            $this->PaymentMethodIndicator = \Barion\Helpers\jget($json, 'PaymentMethodIndicator');
            $this->ProvisionAttempts = \Barion\Helpers\jget($json, 'ProvisionAttempts');
            $this->TransactionalActivityPerDay = \Barion\Helpers\jget($json, 'TransactionalActivityPerDay');
            $this->TransactionalActivityPerYear = \Barion\Helpers\jget($json, 'TransactionalActivityPerYear');
            $this->SuspiciousActivityIndicator = \Barion\Helpers\jget($json, 'SuspiciousActivityIndicator');
        }
    }
}