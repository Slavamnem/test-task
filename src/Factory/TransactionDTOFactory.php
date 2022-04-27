<?php declare(strict_types=1);

namespace App\Factory;

use App\DTO\SourceFileLineDTO;
use App\DTO\TransactionDTO;
use App\Enum\AccountTypeEnum;
use App\Enum\CurrencyEnum;
use App\Enum\TransactionTypeEnum;
use App\VO\Money;

class TransactionDTOFactory
{
    /**
     * @param SourceFileLineDTO $sourceFileLineDTO
     * @return TransactionDTO
     * @throws \Exception
     */
    public static function createTransactionDTOFromSourceFileLineDTO(SourceFileLineDTO $sourceFileLineDTO): TransactionDTO
    {
        return new TransactionDTO(
            \DateTime::createFromFormat('Y-m-d', $sourceFileLineDTO->getDate()),
            $sourceFileLineDTO->getUserId(),
            new AccountTypeEnum($sourceFileLineDTO->getAccountType()),
            new TransactionTypeEnum($sourceFileLineDTO->getTransactionType()),
            new Money($sourceFileLineDTO->getAmount(), new CurrencyEnum($sourceFileLineDTO->getCurrency()))
        );
    }
}
