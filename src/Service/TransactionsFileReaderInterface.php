<?php declare(strict_types=1);

namespace App\Service;

use App\Collection\TransactionsCollection;

interface TransactionsFileReaderInterface
{
    /**
     * @param string $transactionsFileName
     * @param int $userId
     * @param int $currentTransactionLine
     * @return TransactionsCollection
     * @throws \Exception
     */
    public function getAllUserTransactionsUpToCurrent(string $transactionsFileName, int $userId, int $currentTransactionLine): TransactionsCollection;
}
