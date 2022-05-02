<?php

declare(strict_types=1);

namespace App\DTO;

class ExchangeRatesResponseDTO
{
    public function __construct(private string $base, private string $date, private array $rates)
    {
    }

    public function getBase(): string
    {
        return $this->base;
    }

    public function getDate(): string
    {
        return $this->date;
    }

    public function getRates(): array
    {
        return $this->rates;
    }

    public function hasRate(string $currencyCode): bool
    {
        return !empty($this->rates[$currencyCode]);
    }

    public function getRate(string $currencyCode): float
    {
        return $this->rates[$currencyCode];
    }
}
