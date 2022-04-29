<?php

declare(strict_types=1);

namespace App\Service;

use App\Collection\TransactionsCollection;
use App\Factory\CommissionRulesFacadeFactory;

class CommissionCalculationService implements CommissionCalculationServiceInterface
{
    public function __construct(private CommissionRulesFacadeFactory $commissionRulesFacadeFactory)
    {
    }

    public function calculateCommission(TransactionsCollection $userHistoryUpToCurrentTransaction): float
    {
        return $this->commissionRulesFacadeFactory->makeCommissionRulesFacade()->calculateCommission($userHistoryUpToCurrentTransaction);

        //TODO notes (comments(FileReader and here), commented code(FileReader), code style, psr-12)
        //TODO documentation
    }
}
