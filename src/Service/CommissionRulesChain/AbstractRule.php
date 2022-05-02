<?php

declare(strict_types=1);

namespace App\Service\CommissionRulesChain;

use App\Collection\TransactionsCollection;
use App\Exception\NotFoundAnyCommissionRuleException;
use App\Service\MoneyCalculator\MoneyCalculatorInterface;

abstract class AbstractRule
{
    protected AbstractRule $nextRule;

    public function __construct(protected MoneyCalculatorInterface $moneyCalculator) {}

    public function calculateCommission(TransactionsCollection $userHistoryUpToCurrentTransaction): float
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

    abstract protected function getLastUserTransactionCommission(TransactionsCollection $userHistoryUpToCurrentTransaction): float;

    abstract protected function isAppropriateRule(TransactionsCollection $userHistoryUpToCurrentTransaction): bool;
}
