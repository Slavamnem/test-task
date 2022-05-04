<?php

declare(strict_types=1);

namespace App\Http;

use App\DTO\ExchangeRatesResponseDTO;

class ExchangeRatesHttpProxy implements ExchangeRatesHttpInterface
{
    private ExchangeRatesResponseDTO $cachedExchangeRatesResponseDTO;

    public function __construct(private ExchangeRatesHttp $exchangeRatesHttp)
    {
    }

    public function getCurrentExchangeRates(): ExchangeRatesResponseDTO
    {
        if (empty($this->cachedExchangeRatesResponseDTO)) {
            $this->cachedExchangeRatesResponseDTO = $this->exchangeRatesHttp->getCurrentExchangeRates();
        }

        return $this->cachedExchangeRatesResponseDTO;
    }
}
