<?php declare(strict_types=1);

namespace App\Helper;

use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Validation;

class ValidationHelper
{
    /**
     * @param $object
     * @param null $constraints
     * @param null $groups
     */
    public static function validateAndThrowException($object, $constraints = null, $groups = null): void
    {
        $validator = Validation::createValidatorBuilder()->enableAnnotationMapping()->getValidator();

        $errors = $validator->validate($object, $constraints, $groups);

        if (count($errors) > 0) {
            throw new BadRequestHttpException(self::getErrorMessage($errors));
        }
    }

    /**
     * @param ConstraintViolationListInterface $constraintViolationList
     * @return string
     */
    private static function getErrorMessage(ConstraintViolationListInterface $constraintViolationList): string
    {
        $errorMessages = [];

        foreach ($constraintViolationList as $constraintViolation) {
            $errorMessages[] = $constraintViolation->getMessage();
        }

        return implode('; ', $errorMessages);
    }
}
