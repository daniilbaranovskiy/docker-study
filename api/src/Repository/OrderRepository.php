<?php

namespace App\Repository;

use App\Entity\Order;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Order>
 *
 * @method Order|null find($id, $lockMode = null, $lockVersion = null)
 * @method Order|null findOneBy(array $criteria, array $orderBy = null)
 * @method Order[]    findAll()
 * @method Order[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OrderRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Order::class);
    }

    /**
     * @param int $customer_id
     * @return float|int|mixed|string
     */
    public function getAllOrdersByCustomerId(int $customer_id)
    {
        return $this->createQueryBuilder('o')
            ->join('o.customer', 'c')
            ->andWhere("c.id = :customer_id")
            ->setParameter("customer_id", $customer_id)
            ->getQuery()
            ->getResult();
    }
}
