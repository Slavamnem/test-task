<?php declare(strict_types=1);

namespace App\Enum;

/**
 * @method static self USD
 * @method static self EUR
 * @method static self JPY
 * @method static self UAH
 */
class CurrencyEnum extends AbstractEnum
{
    public const USD = 'USD';
    public const EUR = 'EUR';
    public const JPY = 'JPY';
    public const UAH = 'UAH';

    protected static array $_enums = [
        1 => self::USD,
        2 => self::EUR,
        3 => self::JPY,
        4 => self::UAH,
    ];

    /**
     * @return CurrencyEnum
     */
    public static function getDefaultCurrency(): CurrencyEnum
    {
        return CurrencyEnum::EUR();
    }
}
