<?php declare(strict_types=1);

namespace App\Service\CommissionRulesChain\WithdrawChain;

use App\Collection\TransactionsCollection;
use App\Enum\AccountTypeEnum;
use App\Enum\TransactionTypeEnum;
use App\Service\CommissionRulesChain\AbstractRule;

class BusinessAccountWithdrawRule extends AbstractRule
{
    private const COMMISSION_PERCENT = 0.5;

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
        return (
            $userTransactionsCollection->getLastTransaction()->getTransactionTypeEnum() == TransactionTypeEnum::Withdraw &&
            $userTransactionsCollection->getLastTransaction()->getAccountTypeEnum() == AccountTypeEnum::Business
        );
    }
}
