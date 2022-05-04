<?php

declare(strict_types=1);

namespace App\Util;

trait EnumToArrayTrait
{
    public static function getValues(): array
    {
        return array_column(self::cases(), 'value');
    }
}
