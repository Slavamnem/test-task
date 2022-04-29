<?php

declare(strict_types=1);

namespace App\Exception;

class NotTheSameCurrenciesOperationException extends \Exception
{
    protected $message = 'Currencies must be the same for addition or subtraction';
}
