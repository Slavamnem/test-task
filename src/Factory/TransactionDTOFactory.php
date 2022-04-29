<?php declare(strict_types=1);

namespace App\Factory;

use App\DTO\SourceFileLineDTO;
use App\DTO\TransactionDTO;
use App\Enum\AccountTypeEnum;
use App\Enum\CurrencyEnum;
use App\Enum\TransactionTypeEnum;
use App\VO\Money;
use DateTime;

class TransactionDTOFactory
{
    public static function createTransactionDTOFromSourceFileLineDTO(SourceFileLineDTO $sourceFileLineDTO): TransactionDTO
    {
        return new TransactionDTO(
            DateTime::createFromFormat('Y-m-d', $sourceFileLineDTO->getDate()),
            $sourceFileLineDTO->getUserId(),
            AccountTypeEnum::from($sourceFileLineDTO->getAccountType()),
            TransactionTypeEnum::from($sourceFileLineDTO->getTransactionType()),
            new Money($sourceFileLineDTO->getAmount(), CurrencyEnum::from($sourceFileLineDTO->getCurrency()))
        );
    }
}
