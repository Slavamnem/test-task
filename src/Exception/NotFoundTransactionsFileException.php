<?php

declare(strict_types=1);

namespace App\Exception;

class NotFoundTransactionsFileException extends \Exception
{
    protected $message = 'Given file path does not exist. Check your file path.';
}
