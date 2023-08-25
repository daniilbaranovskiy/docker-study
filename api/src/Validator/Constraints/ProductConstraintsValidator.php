<?php

namespace App\Validator\Constraints;

use App\Entity\Product;
use Symfony\Component\HttpFoundation\File\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class ProductConstraintsValidator extends ConstraintValidator
{

    /**
     * @param $value
     * @param Constraint $constraint
     * @return void
     */
    public function validate($value, Constraint $constraint): void
    {
        if (!$constraint instanceof ProductConstraints){
            throw new UnexpectedTypeException($constraint, ProductConstraints::class);
        }
        if (!$value instanceof Product){
            throw new UnexpectedTypeException($constraint, Product::class);
        }
        if (empty($value->getName())){
            $this->context->addViolation("Name is empty");
        }
    }
}