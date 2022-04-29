<?php declare(strict_types=1);

namespace App\Service\CommissionRulesChain\WithdrawChain;

use App\Collection\TransactionsCollection;
use App\Enum\AccountTypeEnum;
use App\Enum\TransactionTypeEnum;
use App\Service\CommissionRulesChain\AbstractRule;

class BusinessAccountWithdrawRule extends AbstractRule
{
    private const COMMISSION_PERCENT = 0.5;

    protected function getLastUserTransactionCommission(TransactionsCollection $userHistoryUpToCurrentTransaction): float
    {
        return $userHistoryUpToCurrentTransaction
            ->getLastTransaction()
            ->getMoney()
            ->multiply(self::COMMISSION_PERCENT / 100)
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
