<?php declare(strict_types=1);

namespace App\Service;

use App\Collection\TransactionsCollection;

interface CommissionCalculationServiceInterface
{
    public function calculateCommission(TransactionsCollection $userTransactionsCollection): float;
}
