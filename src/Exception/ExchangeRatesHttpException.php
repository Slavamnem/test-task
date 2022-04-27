<?php declare(strict_types=1);

namespace App\Exception;

class ExchangeRatesHttpException extends \Exception
{
    public const BASE_MESSAGE = 'Ошибка при обращении к апи получения курса валют. ';

    public function __construct($message = "", $code = 0, \Throwable $previous = null)
    {
        parent::__construct(self::BASE_MESSAGE . $message, $code, $previous);
    }
}
