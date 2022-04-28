<?php declare(strict_types=1);

namespace App\Service\CommissionRulesChain\DepositChain;

use App\Collection\TransactionsCollection;
use App\Enum\TransactionTypeEnum;
use App\Service\CommissionRulesChain\AbstractRule;

class DefaultDepositRule extends AbstractRule
{
    private const COMMISSION_PERCENT = 0.03;

    /**
     * @param TransactionsCollection $userTransactionsCollection
     * @return float
     */
    protected function getLastUserTransactionCommission(TransactionsCollection $userTransactionsCollection): float
    {
        return $userTransactionsCollection
            ->getLastTransaction()
            ->getMoney()
            ->multiply(self::COMMISSION_PERCENT / 100)
            ->getValue();
    }

    /**
     * @param TransactionsCollection $userTransactionsCollection
     * @return bool
     */
    protected function isAppropriateRule(TransactionsCollection $userTransactionsCollection): bool
    {
        return $userTransactionsCollection->getLastTransaction()->getTransactionType()->isEqual(TransactionTypeEnum::DEPOSIT());
    }
}
