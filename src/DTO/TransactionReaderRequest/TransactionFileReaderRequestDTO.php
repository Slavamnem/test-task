<?php declare(strict_types=1);

namespace App\DTO\TransactionReaderRequest;

class TransactionFileReaderRequestDTO extends AbstractTransactionReaderRequestDTO
{
    public function __construct(private string $transactionsFileName) {}

    public function getTransactionsFileName(): string
    {
        return $this->transactionsFileName;
    }
}
