<?php

namespace App\Validator\Constraints;

use App\Entity\Orders;
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

        $validStatuses = ["pending", "processing", "completed"];
        if (!in_array($value->getStatus(), $validStatuses)) {
            $this->context->addViolation("Invalid order status.");
        }

        $validPaymentMethod = ["cash", "card"];
        if (!in_array($value->getPaymentMethod(), $validPaymentMethod)) {
            $this->context->addViolation("Invalid payment method.");
        }
    }
}