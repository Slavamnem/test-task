<?php

declare(strict_types=1);

namespace App\Service\CommissionRulesChain;

use App\Collection\TransactionsCollection;
use App\VO\Money;

interface CommissionRulesFacadeInterface
{
    public function calculateCommission(TransactionsCollection $userHistoryUpToCurrentTransaction): Money;
}
