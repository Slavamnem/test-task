<?php declare(strict_types=1);

namespace App\Enum;

use App\Util\EnumTrait;

enum TransactionTypeEnum: string
{
    use EnumTrait;

    case Deposit = 'deposit';
    case Withdraw = 'withdraw';
}
