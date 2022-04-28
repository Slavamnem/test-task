<?php declare(strict_types=1);

namespace App\Service\CommissionRulesChain;

use App\Collection\TransactionsCollection;

interface CommissionRulesFacadeInterface
{
    /**
     * @param TransactionsCollection $userTransactionsCollection
     * @return float
     */
    public function calculateCommission(TransactionsCollection $userTransactionsCollection): float;
}
