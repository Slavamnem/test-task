<?php declare(strict_types=1);

namespace App\Http;

use App\DTO\ExchangeRatesResponseDTO;

interface ExchangeRatesHttpInterface
{
    /**
     * @return ExchangeRatesResponseDTO
     */
    public function getCurrentExchangeRates(): ExchangeRatesResponseDTO;
}
