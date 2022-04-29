<?php declare(strict_types=1);

namespace App\Service\TransactionReader;

use App\DTO\TransactionReaderRequest\AbstractTransactionReaderRequestDTO;

interface TransactionReaderInterface
{
    public function readTransactions(AbstractTransactionReaderRequestDTO $requestDTO): \Generator;
}
