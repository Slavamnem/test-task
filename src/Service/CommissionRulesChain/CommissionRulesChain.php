<?php declare(strict_types=1);

namespace App\Service\CommissionRulesChain;

use App\Collection\TransactionsCollection;

class CommissionRulesChain implements CommissionRulesChainInterface
{
    protected AbstractRule $firstRuleOfChain;

    /**
     * @param AbstractRule $firstRuleOfChain
     */
    public function __construct(AbstractRule $firstRuleOfChain)
    {
        $this->firstRuleOfChain = $firstRuleOfChain;
    }

    /**
     * @param TransactionsCollection $userTransactionsCollection
     * @return float
     * @throws \Exception
     */
    public function calculateCommission(TransactionsCollection $userTransactionsCollection): float
    {
        return $this->firstRuleOfChain->calculateCommission($userTransactionsCollection);
    }
}
