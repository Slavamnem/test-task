<?php declare(strict_types=1);

namespace App\Service;

use App\Collection\TransactionsCollection;
use App\Enum\CurrencyEnum;
use App\VO\Money;

class CommissionCalculationService implements CommissionCalculationServiceInterface
{
    private CurrencyExchangeServiceInterface $currencyExchangeService;

    public function __construct(CurrencyExchangeServiceInterface $currencyExchangeService)
    {
        $this->currencyExchangeService = $currencyExchangeService;
    }

    /**
     * @param TransactionsCollection $userTransactionsCollection
     * @return float
     * @throws \Exception
     */
    public function calculateFee(TransactionsCollection $userTransactionsCollection): float //TODO
    {
        $transactionMoney = $userTransactionsCollection->getLastTransaction()->getMoney();

        return $this->currencyExchangeService
            ->convertMoney($transactionMoney, CurrencyEnum::getDefaultCurrency())
            ->multiply(0.003)
            ->getValue();
    }
}
