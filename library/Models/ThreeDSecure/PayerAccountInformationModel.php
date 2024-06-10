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

namespace Barion\Models\ThreeDSecure;

use Barion\Interfaces\IBarionModel;
use Barion\Helpers\JSON;
use Barion\Enumerations\ThreeDSecure\{
    AccountChangeIndicator,
    AccountCreationIndicator,
    PasswordChangeIndicator,
    PaymentMethodIndicator,
    ShippingAddressUsageIndicator,
    SuspiciousActivityIndicator
};

/**
 * Model containing information about the payer during a 3D-Secure card payment process.
 */
class PayerAccountInformationModel implements IBarionModel
{
    /** 
     * The account number of the payer, if applicable.
     * 
     * @var ?string
     */  
    public ?string $AccountId;
    
    /** 
     * ISO-8601 format timestamp of the creation of the payer account.
     * 
     * @var ?string
     */  
    public ?string $AccountCreated;

    /** 
     * Indicator describing the time since the payer account has been created.
     * 
     * @var AccountCreationIndicator
     */  
    public AccountCreationIndicator $AccountCreationIndicator;
    
    /** 
     * ISO-8601 format timestamp of the last change of the payer account.
     * 
     * @var ?string
     */  
    public ?string $AccountLastChanged;

    /** 
     * Indicator describing the time since the payer account has been changed.
     * 
     * @var AccountChangeIndicator
     */  
    public AccountChangeIndicator $AccountChangeIndicator;
    
    /** 
     * ISO-8601 format timestamp of the last password change of the payer account.
     * 
     * @var ?string
     */  
    public ?string $PasswordLastChanged;
    
    /** 
     * Indicator describing the time since the last password change regarding the payer account.
     * 
     * @var PasswordChangeIndicator
     */  
    public PasswordChangeIndicator $PasswordChangeIndicator;
    
    /** 
     * Number of successful purchases during the last six months made by the payer account.
     * 
     * @var ?int
     */  
    public ?int $PurchasesInTheLastSixMonths;
    
    /** 
     * ISO-8601 format timestamp when a shipping address was last added to the payer account.
     * 
     * @var ?string
     */ 
    public ?string $ShippingAddressAdded;
    
    /** 
     * Indicator describing how long ago was the shipping address added to the payer account.
     * 
     * @var ShippingAddressUsageIndicator
     */  
    public ShippingAddressUsageIndicator $ShippingAddressUsageIndicator;
    
    /** 
     * ISO-8601 format timestamp when a payment method was last added to the payer account.
     * 
     * @var ?string
     */ 
    public ?string $PaymentMethodAdded;
    
    /** 
     * Indicator describing how long ago was the payment method added to the payer account.
     * 
     * @var PaymentMethodIndicator
     */ 
    public PaymentMethodIndicator $PaymentMethodIndicator;
    
    /** 
     * Number of successfully added payment methods (e.g. bank cards) the payer account during the last 24 hours.
     * 
     * @var ?int
     */
    public ?int $ProvisionAttempts;
    
    /** 
     * Number of successful transactions made by the payer account in the last 24 hours.
     * 
     * @var ?int
     */
    public ?int $TransactionalActivityPerDay;
    
    /** 
     * Number of successful transactions made by the payer account in the last 365 days.
     * 
     * @var ?int
     */
    public ?int $TransactionalActivityPerYear;
    
    /** 
     * Indicator about any suspicious activity regarding the payer account.
     * 
     * @var SuspiciousActivityIndicator
     */  
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
            $this->PurchasesInTheLastSixMonths = JSON::getInt($json, 'PurchasesInTheLastSixMonths');
            $this->ShippingAddressAdded = JSON::getString($json, 'ShippingAddressAdded');
            $this->ShippingAddressUsageIndicator = ShippingAddressUsageIndicator::from(JSON::getString($json, 'ShippingAddressUsageIndicator') ?? '');
            $this->PaymentMethodAdded = JSON::getString($json, 'PaymentMethodAdded');
            $this->PaymentMethodIndicator = PaymentMethodIndicator::from(JSON::getString($json, 'PaymentMethodIndicator') ?? '');
            $this->ProvisionAttempts = JSON::getInt($json, 'ProvisionAttempts');
            $this->TransactionalActivityPerDay = JSON::getInt($json, 'TransactionalActivityPerDay');
            $this->TransactionalActivityPerYear = JSON::getInt($json, 'TransactionalActivityPerYear');
            $this->SuspiciousActivityIndicator = SuspiciousActivityIndicator::from(JSON::getString($json, 'SuspiciousActivityIndicator') ?? '');
        }
    }
}