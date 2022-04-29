<?php declare(strict_types=1);

namespace App\DTO;

class TransactionWithUserHistoryDTO
{
    public function __construct(private TransactionDTO $transactionDTO) {}

    public function getTransaction(): TransactionDTO
    {
        return $this->transactionDTO;
    }

    public function getTransactionUserHistory(): array
    {

    }

    public function getAllUserTransaction(): array
    {
        return $this->getTransactionUserHistory() + [$this->getTransaction()];
    }
}
