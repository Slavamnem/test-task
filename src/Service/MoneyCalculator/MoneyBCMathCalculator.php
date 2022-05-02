<?php

declare(strict_types=1);

namespace App\Service\MoneyCalculator;

use App\Exception\NotTheSameCurrenciesOperationException;
use App\VO\Money;

class MoneyBCMathCalculator implements MoneyCalculatorInterface
{
    public function __construct(private int $precision)
    {
    }

    public function add(Money $money1, Money $money2): Money
    {
        if (!$money1->getCurrency() === $money2->getCurrency()) {
            throw new NotTheSameCurrenciesOperationException();
        }

        $newValue = (float) bcadd((string) $money1->getValue(), (string) $money2->getValue(), $this->precision);

        return new Money($newValue, $money1->getCurrency());
    }

    public function minus(Money $money1, Money $money2): Money
    {
        if (!$money1->getCurrency() === $money2->getCurrency()) {
            throw new NotTheSameCurrenciesOperationException();
        }

        $newValue = (float) bcsub((string) $money1->getValue(), (string) $money2->getValue(), $this->precision);

        if ($newValue < 0) {
            $newValue = 0.00;
        }

        return new Money($newValue, $money1->getCurrency());
    }

    public function multiply(Money $money, float $num): Money
    {
        $newValue = (float) bcmul((string) $money->getValue(), (string) $num, $this->precision);

        return new Money($newValue, $money->getCurrency());
    }

    public function divide(Money $money, float $num): Money
    {
        $newValue = (float) bcdiv((string) $money->getValue(), (string) $num, $this->precision);

        return new Money($newValue, $money->getCurrency());
    }

    public function getPercent(Money $money, float $percent): Money
    {
        $newValue = (float) bcdiv(
            bcmul((string) $money->getValue(), (string) $percent, $this->precision),
            '100',
            $this->precision
        );

        return new Money($newValue, $money->getCurrency());
    }

    public function roundUp(Money $money): Money
    {
        $currencyPrecision = $money->getCurrency()->getPrecision();

        $oldValue = $money->getValue();

        // analog of: ceil($oldValue * (10 ** $currencyPrecision)) / (10 ** $currencyPrecision);
        $newValue = (float) bcdiv(
            (string) ceil(
                (float) bcmul(
                    (string) $oldValue,
                    bcpow('10', (string) $currencyPrecision),
                    $this->precision
                )
            ),
            bcpow('10', (string) $currencyPrecision),
            $this->precision
        );

        return new Money($newValue, $money->getCurrency());
    }
}
