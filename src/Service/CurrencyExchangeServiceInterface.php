<?php declare(strict_types=1);

namespace App\Service;

use App\Enum\CurrencyEnum;
use App\VO\Money;

interface CurrencyExchangeServiceInterface
{
    /**
     * @param Money $money
     * @param CurrencyEnum $newCurrency
     * @return Money
     * @throws \Exception
     */
    public function convertMoney(Money $money, CurrencyEnum $newCurrency): Money;

    /**
     * @param Money $money
     * @return Money
     * @throws \Exception
     */
    public function convertMoneyToDefaultCurrency(Money $money): Money;
}
