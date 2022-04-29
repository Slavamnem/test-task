<?php

declare(strict_types=1);

namespace App\Service\CommissionRulesChain;

use App\Collection\TransactionsCollection;

class CommissionRulesChain implements CommissionRulesChainInterface
{
    public function __construct(protected AbstractRule $firstRuleOfChain)
    {
    }

    public function calculateCommission(TransactionsCollection $userHistoryUpToCurrentTransaction): float
    {
        return $this->firstRuleOfChain->calculateCommission($userHistoryUpToCurrentTransaction);
    }
}
