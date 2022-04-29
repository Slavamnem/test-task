<?php declare(strict_types=1);

namespace App\Http;

use App\DTO\ExchangeRatesResponseDTO;
use App\Exception\ExchangeRatesHttpException;
use Symfony\Component\HttpClient\CurlHttpClient;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class ExchangeRatesHttp implements ExchangeRatesHttpInterface
{
    const EXCHANGE_RATES_API_URL = 'https://developers.paysera.com/tasks/api/currency-exchange-rates';

    private HttpClientInterface $httpClient;

    public function __construct()
    {
        $this->httpClient = new CurlHttpClient();
    }

    public function getCurrentExchangeRates(): ExchangeRatesResponseDTO
    {
        try {
            $exchangeRatesResponse = $this->httpClient->request('GET', self::EXCHANGE_RATES_API_URL);

            $responseData = json_decode($exchangeRatesResponse->getContent(), true);

            return new ExchangeRatesResponseDTO($responseData['base'], $responseData['date'], $responseData['rates']);
        } catch (\Throwable $exception) {
            throw new ExchangeRatesHttpException($exception->getMessage());
        }
    }
}
