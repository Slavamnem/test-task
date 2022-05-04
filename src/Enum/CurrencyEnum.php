<?php

declare(strict_types=1);

namespace App\Enum;

enum CurrencyEnum: string
{
    case Usd = 'USD';
    case Eur = 'EUR';
    case Jpy = 'JPY';

    public function getPrecision(): int
    {
        return match ($this) {
            self::Usd, self::Eur => 2,
            self::Jpy => 0
        };
    }
}
