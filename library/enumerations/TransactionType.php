<?php

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