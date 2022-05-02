<?php

declare(strict_types=1);

namespace App\Service\CommissionRulesChain;

use App\Collection\TransactionsCollection;
use App\VO\Money;

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

    public function calculateCommission(TransactionsCollection $userHistoryUpToCurrentTransaction): Money
    {
        return $this->rulesChains[$userHistoryUpToCurrentTransaction->getLastTransaction()->getTransactionTypeEnum()->value]->calculateCommission($userHistoryUpToCurrentTransaction);
    }
}
