<?php

declare(strict_types=1);

namespace App\Service;

interface ValidationServiceInterface
{
    public function validateAndThrowException($object, $constraints = null, $groups = null): void;
}
