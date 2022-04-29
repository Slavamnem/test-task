<?php declare(strict_types=1);

namespace App\Service\TransactionReader;

use App\Collection\TransactionsCollection;
use App\DTO\SourceFileLineDTO;
use App\DTO\TransactionReaderRequest\AbstractTransactionReaderRequestDTO;
use App\DTO\TransactionReaderRequest\TransactionFileReaderRequestDTO;
use App\Factory\TransactionDTOFactory;
use App\Helper\ValidationHelper;

class TransactionFileReader implements TransactionReaderInterface
{
    /**
     * @param TransactionFileReaderRequestDTO $requestDTO
     * @return \Generator
     */
    public function processTransactions(AbstractTransactionReaderRequestDTO $requestDTO): \Generator
    {
//        $startTime = microtime(true);
        $sourceFile = fopen($requestDTO->getTransactionsFileName(), 'r');

        $currentFileLine = 1;

        while (($sourceFileLine = fgetcsv($sourceFile)) !== FALSE) {
            $sourceFileLineDTO = new SourceFileLineDTO($sourceFileLine[0], (int)$sourceFileLine[1], $sourceFileLine[2], $sourceFileLine[3], (float)$sourceFileLine[4], $sourceFileLine[5]);
            ValidationHelper::validateAndThrowException($sourceFileLineDTO);

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

        while (($sourceFileLine = fgetcsv($transactionsFile)) !== FALSE) {
            $sourceFileLineDTO = new SourceFileLineDTO($sourceFileLine[0], (int)$sourceFileLine[1], $sourceFileLine[2], $sourceFileLine[3], (float)$sourceFileLine[4], $sourceFileLine[5]);

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
