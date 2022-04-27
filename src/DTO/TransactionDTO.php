<?php declare(strict_types=1);

namespace App\DTO;

use App\Enum\AccountTypeEnum;
use App\Enum\CurrencyEnum;
use App\Enum\TransactionTypeEnum;
use App\VO\Money;

class TransactionDTO
{
    private \DateTime $date;

    private int $userId;

    private AccountTypeEnum $accountType;

    private TransactionTypeEnum $transactionType;

    private Money $money;

    private $text;

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
        $this->text = str_repeat("kjnvdksjfvndkjf", 100000);
    }

    /**
     * @param SourceFileLineDTO $sourceFileLineDTO
     * @return TransactionDTO
     * @throws \Exception
     */
    public static function createFromSourceFileLineDTO(SourceFileLineDTO $sourceFileLineDTO): TransactionDTO
    {
        $transactionDTO = new self();

        $transactionDTO->date = \DateTime::createFromFormat('Y-m-d', $sourceFileLineDTO->getDate());
        $transactionDTO->userId = $sourceFileLineDTO->getUserId();
        $transactionDTO->accountType = new AccountTypeEnum($sourceFileLineDTO->getAccountType());
        $transactionDTO->transactionType = new TransactionTypeEnum($sourceFileLineDTO->getTransactionType());
        $transactionDTO->money = new Money($sourceFileLineDTO->getAmount(), new CurrencyEnum($sourceFileLineDTO->getCurrency()));

        return $transactionDTO;
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
