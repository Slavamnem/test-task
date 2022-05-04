<?php

declare(strict_types=1);

namespace App\Exception;

class ExchangeRatesHttpException extends \Exception
{
    private const BASE_MESSAGE = 'Error when accessing the exchange rate API. ';

    public function __construct($message = '')
    {
        parent::__construct(self::BASE_MESSAGE.$message);
    }
}
