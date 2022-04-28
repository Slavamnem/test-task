<?php declare(strict_types=1);

namespace App\Service\CommissionRulesChain;

use App\Collection\TransactionsCollection;

interface CommissionRulesChainInterface
{
    /**
     * @param TransactionsCollection $userTransactionsCollection
     * @return float
     * @throws \Exception
     */
    public function calculateCommission(TransactionsCollection $userTransactionsCollection): float;
}
