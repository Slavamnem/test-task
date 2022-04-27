<?php declare(strict_types=1);

namespace App\DTO;

use App\Enum\AccountTypeEnum;
use App\Enum\TransactionTypeEnum;
use App\VO\Money;

class TransactionDTO
{
    private \DateTime $date;

    private int $userId;

    private AccountTypeEnum $accountType;

    private TransactionTypeEnum $transactionType;

    private Money $money;

    /**
     * @param \DateTime $date
     * @param int $userId
     * @param AccountTypeEnum $accountType
     * @param TransactionTypeEnum $transactionType
     * @param Money $money
     */
    public function __construct(\DateTime $date, int $userId, AccountTypeEnum $accountType, TransactionTypeEnum $transactionType, Money $money)
    {
        $this->date = $date;
        $this->userId = $userId;
        $this->accountType = $accountType;
        $this->transactionType = $transactionType;
        $this->money = $money;
    }

    /**
     * @return \DateTime
     */
    public function getDate(): \DateTime
    {
        return $this->date;
    }

    /**
     * @return int
     */
    public function getUserId(): int
    {
        return $this->userId;
    }

    /**
     * @return AccountTypeEnum
     */
    public function getAccountType(): AccountTypeEnum
    {
        return $this->accountType;
    }

    /**
     * @return TransactionTypeEnum
     */
    public function getTransactionType(): TransactionTypeEnum
    {
        return $this->transactionType;
    }

    /**
     * @return Money
     */
    public function getMoney(): Money
    {
        return $this->money;
    }
}
