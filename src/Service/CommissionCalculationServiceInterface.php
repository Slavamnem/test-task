<?php declare(strict_types=1);

namespace App\Service;

use App\Collection\TransactionsCollection;

interface CommissionCalculationServiceInterface
{
    /**
     * @param TransactionsCollection $userTransactionsCollection
     * @return float
     * @throws \Exception
     */
    public function calculateFee(TransactionsCollection $userTransactionsCollection): float;
}
