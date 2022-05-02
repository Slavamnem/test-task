<?php

declare(strict_types=1);

namespace App\Service;

use App\DTO\ExchangeRatesResponseDTO;
use App\Enum\CurrencyEnum;
use App\Exception\NotFoundExchangeRateException;
use App\Http\ExchangeRatesHttpInterface;
use App\Service\MoneyCalculator\MoneyCalculatorInterface;
use App\VO\Money;

class CurrencyExchangeService implements CurrencyExchangeServiceInterface
{
    private const NOT_FOUND_EXCHANGE_RATE_MESSAGE = 'Exchange rate for currency: %s not found!';

    public function __construct(private ExchangeRatesHttpInterface $exchangeRatesHttp, private MoneyCalculatorInterface $moneyCalculator)
    {
    }

    public function convertMoney(Money $money, CurrencyEnum $newCurrency): Money
    {
        if ($money->getCurrency() === $newCurrency) {
            return $money;
        }

        $exchangeRates = $this->exchangeRatesHttp->getCurrentExchangeRates();

        $this->checkExchangeRateExistence($exchangeRates, $money->getCurrency());
        $this->checkExchangeRateExistence($exchangeRates, $newCurrency);

        $moneyInBaseCurrency = new Money(
            $this->moneyCalculator->divide($money, $exchangeRates->getRate($money->getCurrency()->value))->getValue(),
            CurrencyEnum::from($exchangeRates->getBase())
        );
        $moneyInNewCurrency = new Money(
            $this->moneyCalculator->multiply($moneyInBaseCurrency, $exchangeRates->getRate($newCurrency->value))->getValue(),
            $newCurrency
        );

        return $moneyInNewCurrency;
    }

    public function convertMoneyToDefaultCurrency(Money $money): Money
    {
        return $this->convertMoney($money, CurrencyEnum::getDefaultCurrency());
    }

    private function checkExchangeRateExistence(ExchangeRatesResponseDTO $exchangeRatesResponseDTO, CurrencyEnum $currencyEnum): void
    {
        if (!$exchangeRatesResponseDTO->hasRate($currencyEnum->value)) {
            throw new NotFoundExchangeRateException(sprintf(self::NOT_FOUND_EXCHANGE_RATE_MESSAGE, $currencyEnum->value));
        }
    }
}
