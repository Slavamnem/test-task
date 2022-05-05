<?php

declare(strict_types=1);

namespace App\Service;

use App\Enum\CurrencyEnum;
use App\VO\Money;

interface CurrencyExchangeServiceInterface
{
    public function convertMoney(Money $money, CurrencyEnum $newCurrency): Money;

    public function convertMoneyToDefaultCurrency(Money $money): Money;
}
