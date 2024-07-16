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

namespace Barion\Enumerations;

enum TransactionType : string 
{
    case Commission = "Commission";
    case Shop = "Shop";
    case TransferToExistingUser = "TransferToExistingUser";
    case TransferToTechnicalAccount = "TransferToTechnicalAccount";
    case TransferFromTechnicalAccount = "TransferFromTechnicalAccount";
    case Storno = "Storno";
    case WithdrawFee = "WithdrawFee";
    case ClosureFee = "ClosureFee";
    case StornoBankTransferFee = "StornoBankTransferFee";
    case CashDepositFee = "CashDepositFee";
    case ForeignDepositFee = "ForeignDepositFee";
    case ForeignWithdrawFee = "ForeignWithdrawFee";
    case ForeignClosureFee = "ForeignClosureFee";
    case BankTransferWithdrawFeeStorno = "BankTransferWithdrawFeeStorno";
    case Reserve = "Reserve";
    case StornoReserve = "StornoReserve";
    case CardTopUpFee = "CardTopUpFee";
    case CardProcessingFee = "CardProcessingFee";
    case GatewayFee = "GatewayFee";
    case CardProcessingFeeStorno = "CardProcessingFeeStorno";
    case Unspecified = "Unspecified";
    case In = "In";
    case Withdraw = "Withdraw";
    case CashDeposit = "CashDeposit";
    case ForeignDeposit = "ForeignDeposit";
    case ForeignBankTransferFee = "ForeignBankTransferFee";
    case CashBankTransferFee = "CashBankTransferFee";
    case CustodyFee = "CustodyFee";
    case ForeignWithdraw = "ForeignWithdraw";
    case TransferBack = "TransferBack";
    case CardTopUp = "CardTopUp";
    case CardTopUpBankFee = "CardTopUpBankFee";
    case EmoneySubstractionRestore = "EmoneySubstractionRestore";
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