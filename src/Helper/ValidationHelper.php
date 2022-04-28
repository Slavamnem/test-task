<?php declare(strict_types=1);

namespace App\Helper;

use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Validation;

class ValidationHelper
{
    public static function validateAndThrowException($object, $constraints = null, $groups = null): void
    {
        $validator = Validation::createValidatorBuilder()->enableAnnotationMapping()->getValidator();

        $errors = $validator->validate($object, $constraints, $groups);

        if (count($errors) > 0) {
            throw new BadRequestHttpException("Validation error: " . self::getErrorMessage($errors));
        }
    }

    private static function getErrorMessage(ConstraintViolationListInterface $constraintViolationList): string
    {
        $errorMessages = [];

        foreach ($constraintViolationList as $constraintViolation) {
            $errorMessages[] = $constraintViolation->getMessage();
        }

        return implode('; ', $errorMessages);
    }
}
