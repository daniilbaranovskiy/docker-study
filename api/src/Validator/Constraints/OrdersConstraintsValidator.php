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
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $entityManager;

    /**
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

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

        $productsCount = count($value->getProducts());
        if (empty($productsCount)) {
            $this->context->addViolation("Products cannot be blank.");
        }

        $productsCount = count($value->getProducts());
        if ($productsCount > 3) {
            $this->context->addViolation("Only up to 3 products are allowed per order.");
        }

        if ($value->getOrderSum() > 1000) {
            $this->context->addViolation("Order sum cannot exceed 1000.");
        }

        $user = $value->getUser();
        if ($user) {
            $userOrders = $this->entityManager->getRepository(Orders::class)->findBy(['user' => $user]);

            if (count($userOrders) >= 2) {
                $this->context->addViolation("User cannot have more than 2 orders . ");
            }
        }
    }
}