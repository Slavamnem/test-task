<?php declare(strict_types=1);

namespace App\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class SourceFileLineDTO
{
    /**
     * @var string
     *
     * @Assert\NotBlank(message="SourceFileLineDTO date field is missing")
     * @Assert\Type(type="string")
     * @Assert\Date
     */
    private string $date;

    /**
     * @var int
     *
     * @Assert\NotBlank(message="SourceFileLineDTO userId field is missing")
     * @Assert\Type(type="integer")
     */
    private int $userId;

    /**
     * @var string
     *
     * @Assert\NotBlank(message="SourceFileLineDTO AccountType field is missing")
     * @Assert\Type(type="string")
     */
    private string $accountType;

    /**
     * @var string
     *
     * @Assert\NotBlank(message="SourceFileLineDTO TransactionType field is missing")
     * @Assert\Type(type="string")
     */
    private string $transactionType;

    /**
     * @var float
     *
     * @Assert\NotBlank(message="SourceFileLineDTO anount field is missing")
     * @Assert\Type(type="float")
     */
    private float $amount;

    /**
     * @var string
     *
     * @Assert\NotBlank(message="SourceFileLineDTO currency field is missing")
     * @Assert\Type(type="string")
     */
    private string $currency;

    /**
     * @param string $date
     * @param int $userId
     * @param string $accountType
     * @param string $transactionType
     * @param float $amount
     * @param string $currency
     */
    public function __construct(string $date, int $userId, string $accountType, string $transactionType, float $amount, string $currency)
    {
        $this->date = $date;
        $this->userId = $userId;
        $this->accountType = $accountType;
        $this->transactionType = $transactionType;
        $this->amount = $amount;
        $this->currency = $currency;
    }

    /**
     * @return string
     */
    public function getDate(): string
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
     * @return string
     */
    public function getAccountType(): string
    {
        return $this->accountType;
    }

    /**
     * @return string
     */
    public function getTransactionType(): string
    {
        return $this->transactionType;
    }

    /**
     * @return float
     */
    public function getAmount(): float
    {
        return $this->amount;
    }

    /**
     * @return string
     */
    public function getCurrency(): string
    {
        return $this->currency;
    }
}
