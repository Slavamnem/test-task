<?php

declare(strict_types=1);

namespace App\Exception;

class NotFoundAnyCommissionRuleException extends \Exception
{
    protected $message = 'Not found any appropriate rule for commission calculation.';
}
