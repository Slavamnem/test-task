<?php

declare(strict_types=1);

namespace App\VO;

use App\Enum\CurrencyEnum;

class Money
{
    private float $value;
    private CurrencyEnum $currency;

    public function __construct(float $value, CurrencyEnum $currency)
    {
        $this->value = $value;
        $this->currency = $currency;
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
