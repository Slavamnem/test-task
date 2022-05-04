<?php

declare(strict_types=1);

namespace App\Service;

use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ValidationService implements ValidationServiceInterface
{
    private const VALIDATION_ERROR_MESSAGE = "Validation errors:\n%s";

    private ValidatorInterface $validator;

    public function __construct()
    {
        $this->validator = Validation::createValidatorBuilder()
            ->enableAnnotationMapping()
            ->getValidator()
        ;
    }

    public function validateAndThrowException($object, $constraints = null, $groups = null): void
    {
        $validationErrors = $this->validator->validate($object, $constraints, $groups);

        if (count($validationErrors) > 0) {
            throw new BadRequestHttpException(sprintf(self::VALIDATION_ERROR_MESSAGE, self::getErrorMessage($validationErrors)));
        }
    }

    private function getErrorMessage(ConstraintViolationListInterface $constraintViolationList): string
    {
        $errorMessages = [];

        foreach ($constraintViolationList as $constraintViolation) {
            $errorMessages[] = $constraintViolation->getMessage();
        }

        return implode("\n", $errorMessages);
    }
}
