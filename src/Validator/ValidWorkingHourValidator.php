<?php

namespace App\Validator;

use Symfony\Component\HttpFoundation\File\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use UnexpectedValueException;

#[\Attribute]
class ValidWorkingHourValidator extends ConstraintValidator
{
    private const VALID_MINUTES = ['00', '15', '30', '45'];

    public function validate(mixed $value, Constraint $constraint)
    {
        if (!$constraint instanceof ValidWorkingHour) {
            throw new UnexpectedTypeException($constraint, ValidWorkingHour::class);
        }

        if (null === $value || '' === $value) {
            return;
        }

        if (!is_string($value)) {
            throw new UnexpectedValueException($value, 'string');
        }

        $timeParts = explode(':', $value);

        if (count($timeParts) !== 3) {
            throw new UnexpectedValueException($value, 'HH:MM:SS format');
        }

        [$hours, $minutes, $seconds] = $timeParts;

        if ($seconds !== '00' || !in_array($minutes, self::VALID_MINUTES, true)) {
            $this->context
                ->buildViolation($constraint->message)
                ->setParameter('{{ value }}', $value)
                ->addViolation();
        }
    }
}