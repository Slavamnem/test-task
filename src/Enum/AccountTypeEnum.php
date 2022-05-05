<?php

declare(strict_types=1);

namespace App\Enum;

use App\Util\EnumToArrayTrait;

enum AccountTypeEnum: string
{
    use EnumToArrayTrait;

    case Private = 'private';
    case Business = 'business';
}
