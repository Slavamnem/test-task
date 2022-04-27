<?php declare(strict_types=1);

namespace App\Http;

use App\DTO\ExchangeRatesResponseDTO;
use App\Exception\ExchangeRatesHttpException;
use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;

class ExchangeRatesHttp implements ExchangeRatesHttpInterface
{
    const EXCHANGE_RATES_API_URL = 'https://developers.paysera.com/tasks/api/currency-exchange-rates';

    private ClientInterface $guzzleClient;

    public function __construct()
    {
        $this->guzzleClient = new Client();
    }

    /**
     * @return ExchangeRatesResponseDTO
     * @throws ExchangeRatesHttpException
     */
    public function getCurrentExchangeRates(): ExchangeRatesResponseDTO
    {
        try {
            $exchangeRatesResponse = $this->guzzleClient->get(self::EXCHANGE_RATES_API_URL);

            $responseData = json_decode($exchangeRatesResponse->getBody()->getContents(), true);

            return new ExchangeRatesResponseDTO($responseData['base'], $responseData['date'], $responseData['rates']);
        } catch (\Throwable $exception) {
            throw new ExchangeRatesHttpException($exception->getMessage());
        }
    }
}
