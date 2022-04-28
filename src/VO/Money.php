<?php declare(strict_types=1);

namespace App\VO;

use App\Enum\CurrencyEnum;
use App\Exception\NotTheSameCurrenciesOperationException;

class Money
{
    public const PRECISION = 2;

    private float $value;

    private CurrencyEnum $currency;

    public function __construct(float $value, CurrencyEnum $currency)
    {
        $this->value = $value;
        $this->currency = $currency;
    }

    /**
     * @param Money $money
     * @return Money
     * @throws \Exception
     */
    public function add(Money $money): Money
    {
        if (!$money->getCurrency()->isEqual($this->getCurrency())) {
            throw new NotTheSameCurrenciesOperationException();
        }

        $newValue = (float)bcadd((string)$this->value, (string)$money->value, self::PRECISION);

        return new Money($newValue, $this->getCurrency());
    }

    /**
     * @param Money $money
     * @return Money
     * @throws \Exception
     */
    public function minus(Money $money): Money
    {
        if (!$money->getCurrency()->isEqual($this->getCurrency())) {
            throw new NotTheSameCurrenciesOperationException();
        }

        $newValue = (float)bcsub((string)$this->value, (string)$money->value, self::PRECISION);

        if ($newValue < 0) {
            $newValue = 0.00;
        }

        return new Money($newValue, $this->getCurrency());
    }

    /**
     * @param float $num
     * @return Money
     */
    public function multiply(float $num): Money
    {
        $newValue = (float)bcmul((string)$this->value, (string)$num, self::PRECISION);

        return new Money($newValue, $this->getCurrency());
    }

    /**
     * @param float $num
     * @return Money
     */
    public function divide(float $num): Money
    {
        $newValue = (float)bcdiv((string)$this->value, (string)$num, self::PRECISION);

        return new Money($newValue, $this->getCurrency());
    }

    /**
     * @return float
     */
    public function getValue(): float
    {
        return $this->value;
    }

    /**
     * @return CurrencyEnum
     */
    public function getCurrency(): CurrencyEnum
    {
        return $this->currency;
    }
}
