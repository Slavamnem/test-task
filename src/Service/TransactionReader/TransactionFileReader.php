<?php

declare(strict_types=1);

namespace App\Service\TransactionReader;

use App\Collection\TransactionsCollection;
use App\DTO\SourceFileLineDTO;
use App\DTO\TransactionReaderRequestDTO;
use App\Exception\NotFoundTransactionsFileException;
use App\Factory\TransactionDTOFactory;
use App\Service\ValidationServiceInterface;

class TransactionFileReader implements TransactionReaderInterface
{
    public function __construct(
        private ValidationServiceInterface $validationService,
        private TransactionDTOFactory $transactionDTOFactory,
    ) {
    }

    public function readTransactions(TransactionReaderRequestDTO $requestDTO): \Generator
    {
        $sourceFile = fopen($requestDTO->getTransactionsFilePath(), 'r');

        if (!$sourceFile) {
            throw new NotFoundTransactionsFileException();
        }

        $currentFileLine = 1;

        while (($sourceFileLine = fgetcsv($sourceFile)) !== false) {
            $sourceFileLineDTO = new SourceFileLineDTO(
                $sourceFileLine[0],
                (int) $sourceFileLine[1],
                $sourceFileLine[2],
                $sourceFileLine[3],
                (float) $sourceFileLine[4],
                $sourceFileLine[5]
            );

            $this->validationService->validateAndThrowException($sourceFileLineDTO);

            //For each transaction, I read the file again to get the current transaction's user history, ignoring other transactions.
            //This keeps the memory from overflowing.
            yield $this->getUserHistoryUpToCurrentTransaction(
                $requestDTO->getTransactionsFilePath(),
                $sourceFileLineDTO->getUserId(),
                $currentFileLine
            );

            ++$currentFileLine;
        }

        fclose($sourceFile);
    }

    public function getUserHistoryUpToCurrentTransaction(
        string $transactionsFilePath,
        int $currentTransactionUserId,
        int $currentTransactionLine
    ): TransactionsCollection {
        $userHistoryUpToCurrentTransaction = new TransactionsCollection();

        $transactionsFile = fopen($transactionsFilePath, 'r');

        $fileLine = 1;

        while (($sourceFileLine = fgetcsv($transactionsFile)) !== false) {
            $sourceFileLineDTO = new SourceFileLineDTO(
                $sourceFileLine[0],
                (int) $sourceFileLine[1],
                $sourceFileLine[2],
                $sourceFileLine[3],
                (float) $sourceFileLine[4],
                $sourceFileLine[5]
            );

            if ($sourceFileLineDTO->getUserId() !== $currentTransactionUserId) {
                ++$fileLine;
                continue;
            }

            $userHistoryUpToCurrentTransaction->addTransaction(
                $this->transactionDTOFactory->createTransactionDTOFromSourceFileLineDTO($sourceFileLineDTO)
            );

            if ($fileLine === $currentTransactionLine) {
                break;
            }

            ++$fileLine;
        }

        fclose($transactionsFile);

        return $userHistoryUpToCurrentTransaction;
    }
}
