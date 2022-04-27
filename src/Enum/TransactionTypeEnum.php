<?php declare(strict_types=1);

namespace App\Enum;

/**
 * @method static self DEPOSIT
 * @method static self WITHDRAW
 */
class TransactionTypeEnum extends AbstractEnum
{
    public const DEPOSIT = 'deposit';
    public const WITHDRAW = 'withdraw';

    protected static array $_enums = [
        1 => self::DEPOSIT,
        2 => self::WITHDRAW,
    ];
}
