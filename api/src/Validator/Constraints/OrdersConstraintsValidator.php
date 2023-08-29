<?php

namespace App\Validator\Constraints;

use App\Entity\Orders;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class OrdersConstraintsValidator extends ConstraintValidator
{
    /**
     * @param $value
     * @param Constraint $constraint
     * @return void
     */
    public function validate($value, Constraint $constraint): void
    {
        if (!$constraint instanceof OrdersConstraints) {
            throw new UnexpectedTypeException($constraint, OrdersConstraints::class);
        }

        if (!$value instanceof Orders) {
            throw new UnexpectedTypeException($value, Orders::class);
        }

        if ($value->getOrderSum() > 1000) {
            $this->context->addViolation("Order sum cannot exceed 1000.");
        }
    }
}