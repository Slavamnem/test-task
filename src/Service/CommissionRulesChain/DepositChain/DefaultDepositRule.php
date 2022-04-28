<?php declare(strict_types=1);

namespace App\Service\CommissionRulesChain\DepositChain;

use App\Collection\TransactionsCollection;
use App\Enum\TransactionTypeEnum;
use App\Service\CommissionRulesChain\AbstractRule;

class DefaultDepositRule extends AbstractRule
{
    private const COMMISSION_PERCENT = 0.03;

    protected function getLastUserTransactionCommission(TransactionsCollection $userTransactionsCollection): float
    {
        return $userTransactionsCollection
            ->getLastTransaction()
            ->getMoney()
            ->multiply(self::COMMISSION_PERCENT / 100)
            ->getValue();
    }

    protected function isAppropriateRule(TransactionsCollection $userTransactionsCollection): bool
    {
        return $userTransactionsCollection->getLastTransaction()->getTransactionTypeEnum() == TransactionTypeEnum::Deposit;
    }
}
