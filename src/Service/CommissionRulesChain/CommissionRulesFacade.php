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

    public function calculateCommission(TransactionsCollection $userHistoryUpToCurrentTransaction): float
    {
        return $this->rulesChains[$userHistoryUpToCurrentTransaction->getLastTransaction()->getTransactionTypeEnum()->value]->calculateCommission($userHistoryUpToCurrentTransaction);
    }
}
