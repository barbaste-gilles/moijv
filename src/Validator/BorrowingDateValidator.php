<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class BorrowingDateValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint)
    {
        /* @var $constraint \App\Validator\BorrowingDate */

        if (null === $value || '' === $value) {
            return;
        }
        /** @var \DateTimeInterface $value */
        if($value <= new \DateTime()) {
            $this->context->buildViolation($constraint->message)
                ->addViolation();
        }

    }
}
