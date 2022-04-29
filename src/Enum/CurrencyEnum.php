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
    case Uah = 'UAH';

    public static function getDefaultCurrency(): CurrencyEnum
    {
        return CurrencyEnum::from($_ENV['DEFAULT_CURRENCY']);
    }
}
