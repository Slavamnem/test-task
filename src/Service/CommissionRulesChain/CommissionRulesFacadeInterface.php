<?php declare(strict_types=1);

namespace App\Service\CommissionRulesChain;

use App\Collection\TransactionsCollection;

interface CommissionRulesFacadeInterface
{
    public function calculateCommission(TransactionsCollection $userHistoryUpToCurrentTransaction): float;
}
