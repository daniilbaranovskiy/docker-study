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

        $validMemory = ["64GB", "128GB", "256GB", "512GB", "1TB"];
        if (!in_array($value->getMemory(), $validMemory)) {
            $this->context->addViolation("Invalid memory.");
        }

        $validColor = ["black", "white", "gold", "red"];
        if (!in_array($value->getColor(), $validColor)) {
            $this->context->addViolation("Invalid color.");
        }
    }
}