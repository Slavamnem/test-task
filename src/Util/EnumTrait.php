<?php declare(strict_types=1);

namespace App\Util;

trait EnumTrait
{
    public static function getCasesValues(): array
    {
        return array_map(function ($enum) { return $enum->value; }, self::cases());
    }
}
