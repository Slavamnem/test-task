<?php

declare(strict_types=1);

namespace App\Http;

use App\DTO\ExchangeRatesResponseDTO;
use App\Enum\CurrencyEnum;
use DateTime;

class ExchangeRatesHttpTestProxy implements ExchangeRatesHttpInterface
{
    private const EUR_TO_EUR_EXCHANGE_RATE = 1;
    private const EUR_TO_USD_EXCHANGE_RATE = 1.1497;
    private const EUR_TO_JPY_EXCHANGE_RATE = 129.53;

    public function __construct(private ExchangeRatesHttp $exchangeRatesHttp)
    {
    }

    public function getCurrentExchangeRates(): ExchangeRatesResponseDTO
    {
        return new ExchangeRatesResponseDTO(
            CurrencyEnum::Eur->value,
            (new DateTime('now'))->format('Y-m-d'),
            [
                CurrencyEnum::Eur->value => self::EUR_TO_EUR_EXCHANGE_RATE,
                CurrencyEnum::Usd->value => self::EUR_TO_USD_EXCHANGE_RATE,
                CurrencyEnum::Jpy->value => self::EUR_TO_JPY_EXCHANGE_RATE,
            ]
        );
    }
}
