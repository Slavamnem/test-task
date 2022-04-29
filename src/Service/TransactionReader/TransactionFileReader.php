<?php

declare(strict_types=1);

namespace App\Service\TransactionReader;

use App\Collection\TransactionsCollection;
use App\DTO\SourceFileLineDTO;
use App\DTO\TransactionReaderRequest\AbstractTransactionReaderRequestDTO;
use App\Exception\NotFoundTransactionsFileException;
use App\Factory\TransactionDTOFactory;
use App\Helper\ValidationHelper;

class TransactionFileReader implements TransactionReaderInterface
{
    public function readTransactions(AbstractTransactionReaderRequestDTO $requestDTO): \Generator
    {
//        $startTime = microtime(true);
        $sourceFile = fopen($requestDTO->getTransactionsFileName(), 'r');

        if (!$sourceFile) {
            throw new NotFoundTransactionsFileException();
        }

        $currentFileLine = 1;

        while (($sourceFileLine = fgetcsv($sourceFile)) !== false) {
            $sourceFileLineDTO = new SourceFileLineDTO($sourceFileLine[0], (int)$sourceFileLine[1], $sourceFileLine[2], $sourceFileLine[3], (float)$sourceFileLine[4], $sourceFileLine[5]);
            ValidationHelper::validateAndThrowException($sourceFileLineDTO);

            //For each transaction, I read the file again to get the current transaction's user history, ignoring other transactions. This keeps the memory from overflowing.
            yield $this->getUserHistoryUpToCurrentTransaction($requestDTO->getTransactionsFileName(), $sourceFileLineDTO->getUserId(), $currentFileLine);

            $currentFileLine++;
        }

        fclose($sourceFile);
//        dd("Speed: " . microtime(true) - $startTime);
    }

    public function getUserHistoryUpToCurrentTransaction(string $transactionsFileName, int $currentTransactionUserId, int $currentTransactionLine): TransactionsCollection
    {
        $userHistoryUpToCurrentTransaction = new TransactionsCollection();

        $transactionsFile = fopen($transactionsFileName, 'r');

        $fileLine = 1;

        while (($sourceFileLine = fgetcsv($transactionsFile)) !== false) {
            $sourceFileLineDTO = new SourceFileLineDTO(
                $sourceFileLine[0],
                (int)$sourceFileLine[1],
                $sourceFileLine[2],
                $sourceFileLine[3],
                (float)$sourceFileLine[4],
                $sourceFileLine[5]
            );

            if ($sourceFileLineDTO->getUserId() != $currentTransactionUserId) {
                $fileLine++;
                continue;
            }

            $userHistoryUpToCurrentTransaction->addTransaction(TransactionDTOFactory::createTransactionDTOFromSourceFileLineDTO($sourceFileLineDTO));

            if ($fileLine == $currentTransactionLine) {
                break;
            }

            $fileLine++;
        }

        fclose($transactionsFile);

        return $userHistoryUpToCurrentTransaction;
    }
}
