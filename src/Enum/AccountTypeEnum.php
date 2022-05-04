<?php

declare(strict_types=1);

namespace App\Enum;

enum AccountTypeEnum: string
{
    case Private = 'private';
    case Business = 'business';
}
