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
            $this->AccountId = jget($json, 'AccountId');
            $this->AccountCreated = jget($json, 'AccountCreated');
            $this->AccountCreationIndicator = jget($json, 'AccountCreationIndicator');
            $this->AccountLastChanged = jget($json, 'AccountLastChanged');
            $this->AccountChangeIndicator = jget($json, 'AccountChangeIndicator');
            $this->PasswordLastChanged = jget($json, 'PasswordLastChanged');
            $this->PasswordChangeIndicator = jget($json, 'PasswordChangeIndicator');
            $this->PurchasesInTheLastSixMonths = jget($json, 'PurchasesInTheLastSixMonths');
            $this->ShippingAddressAdded = jget($json, 'ShippingAddressAdded');
            $this->ShippingAddressUsageIndicator = jget($json, 'ShippingAddressUsageIndicator');
            $this->PaymentMethodAdded = jget($json, 'PaymentMethodAdded');
            $this->PaymentMethodIndicator = jget($json, 'PaymentMethodIndicator');
            $this->ProvisionAttempts = jget($json, 'ProvisionAttempts');
            $this->TransactionalActivityPerDay = jget($json, 'TransactionalActivityPerDay');
            $this->TransactionalActivityPerYear = jget($json, 'TransactionalActivityPerYear');
            $this->SuspiciousActivityIndicator = jget($json, 'SuspiciousActivityIndicator');
        }
    }
}