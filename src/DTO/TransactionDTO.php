<?php

declare(strict_types=1);

namespace App\DTO;

use App\Enum\AccountTypeEnum;
use App\Enum\TransactionTypeEnum;
use App\VO\Money;
use DateTime;

class TransactionDTO
{
    public function __construct(
        private DateTime $date,
        private int $userId,
        private AccountTypeEnum $accountTypeEnum,
        private TransactionTypeEnum $transactionTypeEnum,
        private Money $money
    ) {}

    public function getDate(): DateTime
    {
        return $this->date;
    }

    public function getUserId(): int
    {
        return $this->userId;
    }

    public function getAccountTypeEnum(): AccountTypeEnum
    {
        return $this->accountTypeEnum;
    }

    public function getTransactionTypeEnum(): TransactionTypeEnum
    {
        return $this->transactionTypeEnum;
    }

    public function getMoney(): Money
    {
        return $this->money;
    }
}
