<?php

declare(strict_types=1);

namespace App\Http;

use App\DTO\ExchangeRatesResponseDTO;

class ExchangeRatesHttpProxy implements ExchangeRatesHttpInterface
{
    private static $cachedExchangeRatesResponse;

    public function __construct(private ExchangeRatesHttp $exchangeRatesHttp) {}

    public function getCurrentExchangeRates(): ExchangeRatesResponseDTO
    {
        if (empty(self::$cachedExchangeRatesResponse)) {
            self::$cachedExchangeRatesResponse = $this->exchangeRatesHttp->getCurrentExchangeRates();
        }

        return self::$cachedExchangeRatesResponse;
    }
}
