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

namespace Barion\Models\ThreeDSecure;

use Barion\Helpers\JSON;

use Barion\Enumerations\ThreeDSecure\{
    AccountChangeIndicator,
    AccountCreationIndicator,
    PasswordChangeIndicator,
    PaymentMethodIndicator,
    ShippingAddressUsageIndicator,
    SuspiciousActivityIndicator
};

class PayerAccountInformationModel implements \Barion\Interfaces\IBarionModel
{
    public ?string $AccountId;
    public ?string $AccountCreated;
    public AccountCreationIndicator $AccountCreationIndicator;
    public ?string $AccountLastChanged;
    public AccountChangeIndicator $AccountChangeIndicator;
    public ?string $PasswordLastChanged;
    public PasswordChangeIndicator $PasswordChangeIndicator;
    public ?string $PurchasesInTheLastSixMonths;
    public ?string $ShippingAddressAdded;
    public ShippingAddressUsageIndicator $ShippingAddressUsageIndicator;
    public ?string $PaymentMethodAdded;
    public PaymentMethodIndicator $PaymentMethodIndicator;
    public ?string $ProvisionAttempts;
    public ?string $TransactionalActivityPerDay;
    public ?string $TransactionalActivityPerYear;
    public SuspiciousActivityIndicator $SuspiciousActivityIndicator;

    function __construct()
    {
        $this->AccountId = null;
        $this->AccountCreated = null;
        $this->AccountCreationIndicator = AccountCreationIndicator::Unspecified;
        $this->AccountLastChanged = null;
        $this->AccountChangeIndicator = AccountChangeIndicator::Unspecified;
        $this->PasswordLastChanged = null;
        $this->PasswordChangeIndicator = PasswordChangeIndicator::Unspecified;
        $this->PurchasesInTheLastSixMonths = null;
        $this->ShippingAddressAdded = null;
        $this->ShippingAddressUsageIndicator = ShippingAddressUsageIndicator::Unspecified;
        $this->PaymentMethodAdded = null;
        $this->PaymentMethodIndicator = PaymentMethodIndicator::Unspecified;
        $this->ProvisionAttempts = null;
        $this->TransactionalActivityPerDay = null;
        $this->TransactionalActivityPerYear = null;
        $this->SuspiciousActivityIndicator = SuspiciousActivityIndicator::Unspecified;
    }

    public function fromJson(array $json) : void
    {
        if (!empty($json)) {
            $this->AccountId = JSON::getString($json, 'AccountId');
            $this->AccountCreated = JSON::getString($json, 'AccountCreated');
            $this->AccountCreationIndicator = AccountCreationIndicator::from(JSON::getString($json, 'AccountCreationIndicator') ?? '');
            $this->AccountLastChanged = JSON::getString($json, 'AccountLastChanged');
            $this->AccountChangeIndicator = AccountChangeIndicator::from(JSON::getString($json, 'AccountChangeIndicator') ?? '');
            $this->PasswordLastChanged = JSON::getString($json, 'PasswordLastChanged');
            $this->PasswordChangeIndicator = PasswordChangeIndicator::from(JSON::getString($json, 'PasswordChangeIndicator') ?? '');
            $this->PurchasesInTheLastSixMonths = JSON::getString($json, 'PurchasesInTheLastSixMonths');
            $this->ShippingAddressAdded = JSON::getString($json, 'ShippingAddressAdded');
            $this->ShippingAddressUsageIndicator = ShippingAddressUsageIndicator::from(JSON::getString($json, 'ShippingAddressUsageIndicator') ?? '');
            $this->PaymentMethodAdded = JSON::getString($json, 'PaymentMethodAdded');
            $this->PaymentMethodIndicator = PaymentMethodIndicator::from(JSON::getString($json, 'PaymentMethodIndicator') ?? '');
            $this->ProvisionAttempts = JSON::getString($json, 'ProvisionAttempts');
            $this->TransactionalActivityPerDay = JSON::getString($json, 'TransactionalActivityPerDay');
            $this->TransactionalActivityPerYear = JSON::getString($json, 'TransactionalActivityPerYear');
            $this->SuspiciousActivityIndicator = SuspiciousActivityIndicator::from(JSON::getString($json, 'SuspiciousActivityIndicator') ?? '');
        }
    }
}