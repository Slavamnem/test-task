<?php

declare(strict_types=1);

namespace App\Service\CommissionRulesChain\WithdrawChain;

use App\Collection\TransactionsCollection;
use App\Enum\AccountTypeEnum;
use App\Enum\TransactionTypeEnum;
use App\Service\CommissionRulesChain\AbstractRule;
use App\Service\MoneyCalculator\MoneyCalculatorInterface;
use App\VO\Money;

class BusinessAccountWithdrawRule extends AbstractRule
{
    public function __construct(protected MoneyCalculatorInterface $moneyCalculator, private float $commissionPercent)
    {
        parent::__construct($moneyCalculator);
    }

    protected function getLastUserTransactionCommission(TransactionsCollection $userHistoryUpToCurrentTransaction): Money
    {
        return $this->moneyCalculator->roundUp(
            $this->moneyCalculator->getPercent(
                $userHistoryUpToCurrentTransaction->getLastTransaction()->getMoney(),
                $this->commissionPercent
            )
        );
    }

    protected function isAppropriateRule(TransactionsCollection $userHistoryUpToCurrentTransaction): bool
    {
        return
            $userHistoryUpToCurrentTransaction->getLastTransaction()->getTransactionTypeEnum() === TransactionTypeEnum::Withdraw
            && $userHistoryUpToCurrentTransaction->getLastTransaction()->getAccountTypeEnum() === AccountTypeEnum::Business
        ;
    }
}
