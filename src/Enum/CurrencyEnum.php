<?php

declare(strict_types=1);

namespace App\Enum;

use App\Util\EnumTrait;

enum CurrencyEnum: string
{
    use EnumTrait;

    case Usd = 'USD';
    case Eur = 'EUR';
    case Jpy = 'JPY';

    private const CURRENCIES_PRECISION = [
        'USD' => 2,
        'EUR' => 2,
        'JPY' => 0,
    ];

    public static function getDefaultCurrency(): CurrencyEnum
    {
        return self::from($_ENV['DEFAULT_CURRENCY']);
    }

    public function getPrecision(): int
    {
        return self::CURRENCIES_PRECISION[$this->value];
    }
}
