<?php declare(strict_types=1);

namespace App\Service;

use App\Collection\TransactionsCollection;
use App\Factory\CommissionRulesFacadeFactory;

class CommissionCalculationService implements CommissionCalculationServiceInterface
{
    private CommissionRulesFacadeFactory $commissionRulesFacadeFactory;

    /**
     * @param CommissionRulesFacadeFactory $commissionRulesFacadeFactory
     */
    public function __construct(CommissionRulesFacadeFactory $commissionRulesFacadeFactory)
    {
        $this->commissionRulesFacadeFactory = $commissionRulesFacadeFactory;
    }

    /**
     * @param TransactionsCollection $userTransactionsCollection
     * @return float
     */
    public function calculateCommission(TransactionsCollection $userTransactionsCollection): float
    {
        return $this->commissionRulesFacadeFactory->makeCommissionRulesFacade()->calculateCommission($userTransactionsCollection);

        //+++TODO custom exceptions
        //+++TODO money to EUR
        //TODO php8.1
        //TODO convert to .00
        //TODO cache for currency rates api
        //TODO notes
        //TODO interface for readers
        //TODO refactoring: script.php, TransactionsFileReader
    }
}
