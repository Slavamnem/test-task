<?php declare(strict_types=1);

namespace App\Service\CommissionRulesChain;

use App\Collection\TransactionsCollection;

class CommissionRulesFacade implements CommissionRulesFacadeInterface
{
    /**
     * @var array|CommissionRulesChainInterface[]
     */
    private array $rulesChains;

    public function __construct(array $rulesChains)
    {
        $this->rulesChains = $rulesChains;
    }

    /**
     * @param TransactionsCollection $userTransactionsCollection
     * @return float
     * @throws \Exception
     */
    public function calculateCommission(TransactionsCollection $userTransactionsCollection): float
    {
        return $this->rulesChains[$userTransactionsCollection->getLastTransaction()->getTransactionType()->getId()]->calculateCommission($userTransactionsCollection);
    }
}
