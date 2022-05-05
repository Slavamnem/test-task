<?php

declare(strict_types=1);

namespace App\Service\CommissionRulesChain;

use App\Collection\TransactionsCollection;
use App\Exception\NotFoundAnyCommissionRuleException;
use App\Service\MoneyCalculator\MoneyCalculatorInterface;
use App\VO\Money;

abstract class AbstractRule
{
    protected AbstractRule $nextRule;

    public function __construct(protected MoneyCalculatorInterface $moneyCalculator)
    {
    }

    public function calculateCommission(TransactionsCollection $userHistoryUpToCurrentTransaction): Money
    {
        if ($this->isAppropriateRule($userHistoryUpToCurrentTransaction)) {
            return $this->getLastUserTransactionCommission($userHistoryUpToCurrentTransaction);
        } elseif (!empty($this->nextRule)) {
            return $this->nextRule->calculateCommission($userHistoryUpToCurrentTransaction);
        }

        throw new NotFoundAnyCommissionRuleException();
    }

    public function setNextRule(AbstractRule $nextRule): AbstractRule
    {
        $this->nextRule = $nextRule;

        return $this;
    }

    abstract protected function getLastUserTransactionCommission(TransactionsCollection $userHistoryUpToCurrentTransaction): Money;

    abstract protected function isAppropriateRule(TransactionsCollection $userHistoryUpToCurrentTransaction): bool;
}
