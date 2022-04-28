<?php declare(strict_types=1);

namespace App\Collection;

use App\DTO\TransactionDTO;

class TransactionsCollection
{
    /**
     * @var array|TransactionDTO[]
     */
    private array $transactions;

    /**
     * @param TransactionDTO $transactionDTO
     * @return $this
     * @throws \Exception
     */
    public function addTransaction(TransactionDTO $transactionDTO): TransactionsCollection
    {
        $this->transactions[] = $transactionDTO;
        return $this;
    }

    /**
     * @return array|TransactionDTO[]
     */
    public function getTransactions(): array
    {
        return $this->transactions;
    }

    /**
     * @return TransactionDTO
     */
    public function getLastTransaction(): TransactionDTO
    {
        return $this->transactions[count($this->transactions) - 1];
    }

    /**
     * @return int
     */
    public function getSize(): int
    {
        return count($this->transactions);
    }
}
