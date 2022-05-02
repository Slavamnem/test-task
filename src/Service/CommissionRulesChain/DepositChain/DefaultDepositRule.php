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
    private float $commissionPercent;

    public function __construct(protected MoneyCalculatorInterface $moneyCalculator)
    {
        $this->commissionPercent = (float)$_ENV['DEPOSIT_COMMISSION_PERCENT'];

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
        return $userHistoryUpToCurrentTransaction->getLastTransaction()->getTransactionTypeEnum() == TransactionTypeEnum::Deposit;
    }
}
