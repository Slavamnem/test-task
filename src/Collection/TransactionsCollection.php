<?php declare(strict_types=1);

namespace App\Collection;

use App\DTO\TransactionDTO;

class TransactionsCollection
{
    /**
     * @var array|TransactionDTO[]
     */
    private array $transactions;

    public function addTransaction(TransactionDTO $transactionDTO): TransactionsCollection
    {
        $this->transactions[] = $transactionDTO;
        return $this;
    }

    public function getTransactions(): array
    {
        return $this->transactions;
    }

    public function getLastTransaction(): TransactionDTO
    {
        return $this->transactions[count($this->transactions) - 1];
    }

    public function getSize(): int
    {
        return count($this->transactions);
    }
}
