<?php

declare(strict_types=1);

namespace App\Service\CommissionRulesChain\WithdrawChain;

use App\Collection\TransactionsCollection;
use App\Enum\AccountTypeEnum;
use App\Enum\TransactionTypeEnum;
use App\Service\CommissionRulesChain\AbstractRule;
use App\Service\CurrencyExchangeServiceInterface;

class BusinessAccountWithdrawRule extends AbstractRule
{
    private float $commissionPercent;

    public function __construct(protected CurrencyExchangeServiceInterface $currencyExchangeService)
    {
        $this->commissionPercent = (float)$_ENV['WITHDRAW_BUSINESS_ACCOUNT_COMMISSION_PERCENT'];

        parent::__construct($this->currencyExchangeService);
    }

    protected function getLastUserTransactionCommission(TransactionsCollection $userHistoryUpToCurrentTransaction): float
    {
        return $userHistoryUpToCurrentTransaction
            ->getLastTransaction()
            ->getMoney()
            ->multiply($this->commissionPercent / 100)
            ->getValue();
    }

    protected function isAppropriateRule(TransactionsCollection $userHistoryUpToCurrentTransaction): bool
    {
        return (
            $userHistoryUpToCurrentTransaction->getLastTransaction()->getTransactionTypeEnum() == TransactionTypeEnum::Withdraw
            && $userHistoryUpToCurrentTransaction->getLastTransaction()->getAccountTypeEnum() == AccountTypeEnum::Business
        );
    }
}
