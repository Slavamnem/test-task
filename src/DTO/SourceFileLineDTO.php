<?php declare(strict_types=1);

namespace App\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class SourceFileLineDTO
{
    /**
     * @Assert\NotBlank(message="SourceFileLineDTO date field is missing")
     * @Assert\Type(type="string")
     * @Assert\Date
     */
    private string $date;

    /**
     * @Assert\NotBlank(message="SourceFileLineDTO userId field is missing")
     * @Assert\Positive
     * @Assert\Type(type="integer")
     */
    private int $userId;

    /**
     * @Assert\NotBlank(message="SourceFileLineDTO AccountType field is missing")
     * @Assert\Type(type="string")
     * @Assert\Choice(
     *     message="SourceFileLineDTO accountType not in enum scope",
     *     callback={"App\Enum\AccountTypeEnum", "getCasesValues"}
     * )
     */
    private string $accountType;

    /**
     * @Assert\NotBlank(message="SourceFileLineDTO TransactionType field is missing")
     * @Assert\Type(type="string")
     * @Assert\Choice(
     *     message="SourceFileLineDTO transactionType not in enum scope",
     *     callback={"App\Enum\TransactionTypeEnum", "getCasesValues"}
     * )
     */
    private string $transactionType;

    /**
     * @Assert\NotBlank(message="SourceFileLineDTO anount field is missing")
     * @Assert\Positive
     * @Assert\Type(type="float")
     */
    private float $amount;

    /**
     * @Assert\NotBlank(message="SourceFileLineDTO currency field is missing")
     * @Assert\Type(type="string")
     * @Assert\Choice(
     *     message="SourceFileLineDTO currency not in enum scope",
     *     callback={"App\Enum\CurrencyEnum", "getCasesValues"}
     * )
     */
    private string $currency;

    public function __construct(string $date, int $userId, string $accountType, string $transactionType, float $amount, string $currency)
    {
        $this->date = $date;
        $this->userId = $userId;
        $this->accountType = $accountType;
        $this->transactionType = $transactionType;
        $this->amount = $amount;
        $this->currency = $currency;
    }

    public function getDate(): string
    {
        return $this->date;
    }

    public function getUserId(): int
    {
        return $this->userId;
    }

    public function getAccountType(): string
    {
        return $this->accountType;
    }

    public function getTransactionType(): string
    {
        return $this->transactionType;
    }

    public function getAmount(): float
    {
        return $this->amount;
    }

    public function getCurrency(): string
    {
        return $this->currency;
    }
}
