<?php declare(strict_types=1);

namespace App\VO;

use App\Enum\CurrencyEnum;
use App\Exception\NotTheSameCurrenciesOperationException;

class Money
{
    public const PRECISION = 2;

    public function __construct(private float $value, private CurrencyEnum $currency) {}

    public function add(Money $money): Money
    {
        if (!$money->getCurrency() == $this->getCurrency()) {
            throw new NotTheSameCurrenciesOperationException();
        }

        $newValue = (float)bcadd((string)$this->value, (string)$money->value, self::PRECISION);

        return new Money($newValue, $this->getCurrency());
    }

    public function minus(Money $money): Money
    {
        if (!$money->getCurrency() == $this->getCurrency()) {
            throw new NotTheSameCurrenciesOperationException();
        }

        $newValue = (float)bcsub((string)$this->value, (string)$money->value, self::PRECISION);

        if ($newValue < 0) {
            $newValue = 0.00;
        }

        return new Money($newValue, $this->getCurrency());
    }

    public function multiply(float $num): Money
    {
        $newValue = (float)bcmul((string)$this->value, (string)$num, self::PRECISION);

        return new Money($newValue, $this->getCurrency());
    }

    public function divide(float $num): Money
    {
        $newValue = (float)bcdiv((string)$this->value, (string)$num, self::PRECISION);

        return new Money($newValue, $this->getCurrency());
    }

    public function getValue(): float
    {
        return $this->value;
    }

    public function getCurrency(): CurrencyEnum
    {
        return $this->currency;
    }
}
