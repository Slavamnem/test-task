<?php

declare(strict_types=1);

namespace App\Enum;

enum TransactionTypeEnum: string
{
    case Deposit = 'deposit';
    case Withdraw = 'withdraw';
}
