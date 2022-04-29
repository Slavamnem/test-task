<?php declare(strict_types=1);

namespace App\Service\CommissionRulesChain\DepositChain;

use App\Collection\TransactionsCollection;
use App\Enum\TransactionTypeEnum;
use App\Service\CommissionRulesChain\AbstractRule;

class DefaultDepositRule extends AbstractRule
{
    private const COMMISSION_PERCENT = 0.03;

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
        return $userHistoryUpToCurrentTransaction->getLastTransaction()->getTransactionTypeEnum() == TransactionTypeEnum::Deposit;
    }
}
