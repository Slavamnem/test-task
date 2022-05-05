<?php

declare(strict_types=1);

namespace App\Service\TransactionReader;

use App\DTO\TransactionReaderRequestDTO;

interface TransactionReaderInterface
{
    public function readTransactions(TransactionReaderRequestDTO $requestDTO): \Generator;
}
