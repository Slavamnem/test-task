<?php declare(strict_types=1);

namespace App\Service;

use App\DTO\ExchangeRatesResponseDTO;
use App\Enum\CurrencyEnum;
use App\Http\ExchangeRatesHttpInterface;
use App\VO\Money;

class CurrencyExchangeService implements CurrencyExchangeServiceInterface
{
    private const NOT_FOUND_EXCHANGE_RATE_MESSAGE = 'Exchange rate for currency: %s not found!';

    /**
     * @var ExchangeRatesHttpInterface
     */
    private ExchangeRatesHttpInterface $exchangeRatesHttp;

    /**
     * ExchangeService constructor.
     * @param ExchangeRatesHttpInterface $exchangeRatesHttp
     */
    public function __construct(ExchangeRatesHttpInterface $exchangeRatesHttp)
    {
        $this->exchangeRatesHttp = $exchangeRatesHttp;
    }

    /**
     * @param Money $money
     * @param CurrencyEnum $newCurrency
     * @return Money
     * @throws \Exception
     */
    public function convertMoney(Money $money, CurrencyEnum $newCurrency): Money
    {
        if ($money->getCurrency()->isEqual($newCurrency)) {
            return $money;
        }

        $exchangeRates = $this->exchangeRatesHttp->getCurrentExchangeRates();

        $this->checkExchangeRateExistence($exchangeRates, $money->getCurrency());
        $this->checkExchangeRateExistence($exchangeRates, $newCurrency);

        $moneyInBaseCurrency = $money->divide($exchangeRates->getRate($money->getCurrency()->getValue()));
        $moneyInNewCurrency = $moneyInBaseCurrency->multiply($exchangeRates->getRate($newCurrency->getValue()));

        return $moneyInNewCurrency;
    }

    /**
     * @param Money $money
     * @return Money
     * @throws \Exception
     */
    public function convertMoneyToDefaultCurrency(Money $money): Money
    {
        return $this->convertMoney($money, CurrencyEnum::getDefaultCurrency());
    }

    /**
     * @param ExchangeRatesResponseDTO $exchangeRatesResponseDTO
     * @param CurrencyEnum $currencyEnum
     * @return void
     * @throws \Exception
     */
    private function checkExchangeRateExistence(ExchangeRatesResponseDTO $exchangeRatesResponseDTO, CurrencyEnum $currencyEnum): void
    {
        if (!$exchangeRatesResponseDTO->hasRate($currencyEnum->getValue())) {
            throw new \Exception(sprintf(self::NOT_FOUND_EXCHANGE_RATE_MESSAGE, $currencyEnum->getValue()));
        }
    }
}
