<?php

declare(strict_types=1);

namespace App\Service;

use App\Collection\TransactionsCollection;
use App\Factory\CommissionRulesFacadeFactory;

class CommissionCalculationService implements CommissionCalculationServiceInterface
{
    public function __construct(private CommissionRulesFacadeFactory $commissionRulesFacadeFactory) {}

    public function calculateCommission(TransactionsCollection $userHistoryUpToCurrentTransaction): float
    {
        return $this->commissionRulesFacadeFactory->makeCommissionRulesFacade()->calculateCommission($userHistoryUpToCurrentTransaction);

        //+++TODO custom exceptions
        //+++TODO money to EUR
        //+++TODO php8.1
        //+++TODO cache for currency rates api
        //+++TODO convert to .00
        //+++TODO interface for readers
        //+++TODO refactoring TransactionsFileReader
        //+++TODO .env

        //TODO test
        //TODO notes (russian comments, commented code, code style, task description rules list)
        //TODO documentation
    }
}
