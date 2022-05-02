<?php

declare(strict_types=1);

namespace App\Service\MoneyCalculator;

use App\VO\Money;

interface MoneyCalculatorInterface
{
    public function add(Money $money1, Money $money2): Money;

    public function minus(Money $money1, Money $money2): Money;

    public function multiply(Money $money, float $num): Money;

    public function divide(Money $money, float $num): Money;

    public function getPercent(Money $money, float $percentage): Money;
}
