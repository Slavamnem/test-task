<?php

declare(strict_types=1);

namespace App\Service\CommissionRulesChain;

use App\Collection\TransactionsCollection;
use App\VO\Money;

class CommissionRulesChain implements CommissionRulesChainInterface
{
    public function __construct(protected AbstractRule $firstRuleOfChain)
    {
    }

    public function calculateCommission(TransactionsCollection $userHistoryUpToCurrentTransaction): Money
    {
        return $this->firstRuleOfChain->calculateCommission($userHistoryUpToCurrentTransaction);
    }
}
