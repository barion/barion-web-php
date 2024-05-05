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

enum TransactionType : string 
{
    case Commission = "Commission";
    case Shop = "Shop";
    case TransferToExistingUser = "TransferToExistingUser";
    case TransferToTechnicalAccount = "TransferToTechnicalAccount";
    case EMoneyTransferFromTechnicalAccount = "EMoneyTransferFromTechnicalAccount";
    case EMoneyStornoFromTechnicalAccount = "EMoneyStornoFromTechnicalAccount";
    case DomesticBankTansferWithdrawFee = "DomesticBankTansferWithdrawFee";
    case AccountClosureFee = "AccountClosureFee";
    case BankTransferWithdrawFeeStorno = "BankTransferWithdrawFeeStorno";
    case CashTopUpFee = "CashTopUpFee";
    case ForeignBankTransferTopUpFee = "ForeignBankTransferTopUpFee";
    case ForeignBankTransferWithdrawFee = "ForeignBankTransferWithdrawFee";
    case ForeignAccountClosureFee = "ForeignAccountClosureFee";
    case Reserve = "Reserve";
    case StornoReserve = "StornoReserve";
    case CardTopUpFee = "CardTopUpFee";
    case CardProcessingFee = "CardProcessingFee";
    case GatewayFee = "GatewayFee";
    case CardProcessingFeeStorno = "CardProcessingFeeStorno";
    case Unspecified = "Unspecified";
    case DomesticBankTransferTopUp = "DomesticBankTransferTopUp";
    case DomesticBankTransferWithdraw = "DomesticBankTransferWithdraw";
    case CashTopUp = "CashTopUp";
    case ForeignBankTransferTopUp = "ForeignBankTransferTopUp";
    case ForeignBankTransferTopUpProcessingFee = "ForeignBankTransferTopUpProcessingFee";
    case CashTopUpProcessingFee = "CashTopUpProcessingFee";
    case CustodyMonthlyFee = "CustodyMonthlyFee";
    case ForeignBankTransferWithdraw = "ForeignBankTransferWithdraw";
    case IncomingBankTransferReversion = "IncomingBankTransferReversion";
    case CardTopUp = "CardTopUp";
    case CardAcquiringFee = "CardAcquiringFee";
    case CardAcquiringFeeStorno = "CardAcquiringFeeStorno";
    case CardPayment = "CardPayment";
    case Refund = "Refund";
    case RefundToBankCard = "RefundToBankCard";
    case StornoUnSuccessfulRefundToBankCard = "StornoUnSuccessfulRefundToBankCard";
    case UnderReview = "UnderReview";
    case ReleaseReview = "ReleaseReview";
    case BankTransferPayment = "BankTransferPayment";
    case RefundToBankAccount = "RefundToBankAccount";
    case StornoUnSuccessfulRefundToBankAccount = "StornoUnSuccessfulRefundToBankAccount";
    case BankTransferPaymentFee = "BankTransferPaymentFee";
    case BarionBalanceProcessingFee = "BarionBalanceProcessingFee";
}

?>