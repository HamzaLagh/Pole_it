<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class BirthdayValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint)
    {
        /* @var App\Validator\Birthday $constraint */

        // if (null === $value || '' === $value) {
        //     return;
        // }
        //dd($value);


        $aujourdhui = date("Y-m-d");
        $string_value = $value->format('Y-m-d');
        $diff = date_diff(date_create($string_value), date_create($aujourdhui));      // valeur rentrée par le futur inscrit

        if (
            $value == null || $diff->format('%y') < 20
        ) {
            // TODO: implement the validation here
            $this->context->buildViolation($constraint->message)
                ->addViolation();
        }
    }
}
