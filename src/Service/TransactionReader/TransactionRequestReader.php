<?php declare(strict_types=1);

namespace App\Service\TransactionReader;

use App\Collection\TransactionsCollection;
use App\DTO\TransactionReaderRequest\AbstractTransactionReaderRequestDTO;

class TransactionRequestReader implements TransactionReaderInterface
{
    public function processTransactions(AbstractTransactionReaderRequestDTO $requestDTO): \Generator {}
}
