<?php declare(strict_types=1);

namespace App\Service;

use App\Collection\TransactionsCollection;
use App\DTO\SourceFileLineDTO;
use App\Factory\TransactionDTOFactory;

class TransactionsFileReader implements TransactionsFileReaderInterface
{
    public function getAllUserTransactionsUpToCurrent(string $transactionsFileName, int $userId, int $currentTransactionLine): TransactionsCollection
    {
        $transactionsFile = fopen($transactionsFileName, 'r');

        $userTransactionsCollection = new TransactionsCollection();

        $line = 1;

        while (($sourceFileLine = fgetcsv($transactionsFile)) !== FALSE) {
            if ((int)$sourceFileLine[1] != $userId) {
                $line++;
                continue;
            }

            $sourceFileLineDTO = new SourceFileLineDTO($sourceFileLine[0], (int)$sourceFileLine[1], $sourceFileLine[2], $sourceFileLine[3], (float)$sourceFileLine[4], $sourceFileLine[5]);

            $userTransactionsCollection->addTransaction(TransactionDTOFactory::createTransactionDTOFromSourceFileLineDTO($sourceFileLineDTO));

            if ($line == $currentTransactionLine) {
                break;
            }

            $line++;
        }

        fclose($transactionsFile);

        return $userTransactionsCollection;
    }
}
