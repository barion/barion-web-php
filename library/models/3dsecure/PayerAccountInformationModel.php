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

use function Barion\Helpers\jget;
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

    public function fromJson($json)
    {
        if (!empty($json)) {
            $this->AccountId = jget($json, 'AccountId');
            $this->AccountCreated = jget($json, 'AccountCreated');
            $this->AccountCreationIndicator = AccountCreationIndicator::from(jget($json, 'AccountCreationIndicator') ?? '');
            $this->AccountLastChanged = jget($json, 'AccountLastChanged');
            $this->AccountChangeIndicator = AccountChangeIndicator::from(jget($json, 'AccountChangeIndicator') ?? '');
            $this->PasswordLastChanged = jget($json, 'PasswordLastChanged');
            $this->PasswordChangeIndicator = PasswordChangeIndicator::from(jget($json, 'PasswordChangeIndicator') ?? '');
            $this->PurchasesInTheLastSixMonths = jget($json, 'PurchasesInTheLastSixMonths');
            $this->ShippingAddressAdded = jget($json, 'ShippingAddressAdded');
            $this->ShippingAddressUsageIndicator = ShippingAddressUsageIndicator::from(jget($json, 'ShippingAddressUsageIndicator') ?? '');
            $this->PaymentMethodAdded = jget($json, 'PaymentMethodAdded');
            $this->PaymentMethodIndicator = PaymentMethodIndicator::from(jget($json, 'PaymentMethodIndicator') ?? '');
            $this->ProvisionAttempts = jget($json, 'ProvisionAttempts');
            $this->TransactionalActivityPerDay = jget($json, 'TransactionalActivityPerDay');
            $this->TransactionalActivityPerYear = jget($json, 'TransactionalActivityPerYear');
            $this->SuspiciousActivityIndicator = SuspiciousActivityIndicator::from(jget($json, 'SuspiciousActivityIndicator') ?? '');
        }
    }
}