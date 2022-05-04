<?php

declare(strict_types=1);

namespace App\Enum;

use App\Util\EnumToArrayTrait;

enum TransactionTypeEnum: string
{
    use EnumToArrayTrait;

    case Deposit = 'deposit';
    case Withdraw = 'withdraw';
}
