<?php declare(strict_types=1);

namespace App\Service;

use App\Collection\TransactionsCollection;

interface TransactionsFileReaderInterface
{
    public function getAllUserTransactionsUpToCurrent(string $transactionsFileName, int $userId, int $currentTransactionLine): TransactionsCollection;
}
