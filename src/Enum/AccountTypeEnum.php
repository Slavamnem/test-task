<?php declare(strict_types=1);

namespace App\Enum;

/**
 * @method static self PRIVATE
 * @method static self BUSINESS
 */
class AccountTypeEnum extends AbstractEnum
{
    public const PRIVATE = 'private';
    public const BUSINESS = 'business';

    protected static array $_enums = [
        1 => self::PRIVATE,
        2 => self::BUSINESS,
    ];
}
