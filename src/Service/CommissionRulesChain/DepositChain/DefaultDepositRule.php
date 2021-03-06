<?php

declare(strict_types=1);

namespace App\Service\CommissionRulesChain\DepositChain;

use App\Collection\TransactionsCollection;
use App\Enum\TransactionTypeEnum;
use App\Service\CommissionRulesChain\AbstractRule;
use App\Service\MoneyCalculator\MoneyCalculatorInterface;
use App\VO\Money;

class DefaultDepositRule extends AbstractRule
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
        return $userHistoryUpToCurrentTransaction->getLastTransaction()->getTransactionTypeEnum() === TransactionTypeEnum::Deposit;
    }
}
