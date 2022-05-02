<?php

declare(strict_types=1);

namespace App\Service;

use App\Collection\TransactionsCollection;
use App\VO\Money;

interface CommissionCalculationServiceInterface
{
    public function calculateCommission(TransactionsCollection $userTransactionsCollection): Money;
}
