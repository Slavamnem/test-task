<?php

declare(strict_types=1);

namespace App\Enum;

use App\Util\EnumTrait;

enum AccountTypeEnum: string
{
    use EnumTrait;

    case Private = 'private';
    case Business = 'business';
}
