<?php

declare(strict_types=1);

namespace App\DTO;

class TransactionReaderRequestDTO
{
    public function __construct(private string|null $transactionsFilePath)
    {
    }

    public function getTransactionsFilePath(): ?string
    {
        return $this->transactionsFilePath;
    }
}
