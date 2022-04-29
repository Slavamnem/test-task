<?php

declare(strict_types=1);

namespace App\Http;

use App\DTO\ExchangeRatesResponseDTO;
use App\Exception\ExchangeRatesHttpException;
use Symfony\Component\HttpClient\CurlHttpClient;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class ExchangeRatesHttp implements ExchangeRatesHttpInterface
{
    private string $exchangeRatesApiUrl;
    private HttpClientInterface $httpClient;

    public function __construct(string $exchangeRatesApiUrl)
    {
        $this->exchangeRatesApiUrl = $exchangeRatesApiUrl;
        $this->httpClient = new CurlHttpClient();
    }

    public function getCurrentExchangeRates(): ExchangeRatesResponseDTO
    {
        try {
            $exchangeRatesResponse = $this->httpClient->request('GET', $this->exchangeRatesApiUrl);

            $responseData = json_decode($exchangeRatesResponse->getContent(), true);

            return new ExchangeRatesResponseDTO($responseData['base'], $responseData['date'], $responseData['rates']);
        } catch (\Throwable $exception) {
            throw new ExchangeRatesHttpException($exception->getMessage());
        }
    }
}
