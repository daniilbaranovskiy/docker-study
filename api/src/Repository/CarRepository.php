<?php

namespace App\Repository;

use App\Entity\Car;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Car>
 *
 * @method Car|null find($id, $lockMode = null, $lockVersion = null)
 * @method Car|null findOneBy(array $criteria, array $orderBy = null)
 * @method Car[]    findAll()
 * @method Car[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CarRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Car::class);
    }

    /**
     * @param int $itemsPerPage
     * @param int $page
     * @param string|null $brandName
     * @param string|null $modelName
     * @param string|null $country
     * @param int|null $year
     * @param int|null $minYear
     * @param int|null $maxYear
     * @param int|null $price
     * @param int|null $minPrice
     * @param int|null $maxPrice
     * @param int|null $quantity
     * @param int|null $minQuantity
     * @param int|null $maxQuantity
     * @param string|null $fuelType
     * @param string|null $transmission
     * @param string|null $color
     * @param int|null $horsepower
     * @param int|null $minHorsepower
     * @param int|null $maxHorsepower
     * @return float|int|mixed|string
     */
    public function getAllCarsWithFilters(int     $itemsPerPage,
                                          int     $page,
                                          ?string $brandName = null,
                                          ?string $modelName = null,
                                          ?string $country = null,
                                          ?int    $year = null,
                                          ?int    $minYear = null,
                                          ?int    $maxYear = null,
                                          ?int    $price = null,
                                          ?int    $minPrice = null,
                                          ?int    $maxPrice = null,
                                          ?int    $quantity = null,
                                          ?int    $minQuantity = null,
                                          ?int    $maxQuantity = null,
                                          ?string $fuelType = null,
                                          ?string $transmission = null,
                                          ?string $color = null,
                                          ?int    $horsepower = null,
                                          ?int    $minHorsepower = null,
                                          ?int    $maxHorsepower = null

    ): mixed
    {
        $queryBuilder = $this->createQueryBuilder("car")
            ->join('car.model', 'model')
            ->andWhere('model.brand LIKE :brandName')
            ->andWhere('model.name LIKE :modelName')
            ->andWhere('model.country LIKE :country')
            ->andWhere('car.fuelType LIKE :fuelType')
            ->andWhere('car.transmission LIKE :transmission')
            ->andWhere('car.color LIKE :color');

        if ($year !== null) {
            $queryBuilder->andWhere('car.year = :year')
                ->setParameter('year', $year);
        }

        if ($minYear !== null) {
            $queryBuilder->andWhere('car.year >= :minYear')
                ->setParameter('minYear', $minYear);
        }

        if ($maxYear !== null) {
            $queryBuilder->andWhere('car.year <= :maxYear')
                ->setParameter('maxYear', $maxYear);
        }

        if ($price !== null) {
            $queryBuilder->andWhere('car.price = :price')
                ->setParameter('price', $price);
        }

        if ($minPrice !== null) {
            $queryBuilder->andWhere('car.price >= :minPrice')
                ->setParameter('minPrice', $minPrice);
        }

        if ($maxPrice !== null) {
            $queryBuilder->andWhere('car.price <= :maxPrice')
                ->setParameter('maxPrice', $maxPrice);
        }

        if ($quantity !== null) {
            $queryBuilder->andWhere('car.quantity = :quantity')
                ->setParameter('quantity', $quantity);
        }

        if ($minQuantity !== null) {
            $queryBuilder->andWhere('car.quantity >= :minQuantity')
                ->setParameter('minQuantity', $minQuantity);
        }

        if ($maxQuantity !== null) {
            $queryBuilder->andWhere('car.quantity <= :maxQuantity')
                ->setParameter('maxQuantity', $maxQuantity);
        }

        if ($horsepower !== null) {
            $queryBuilder->andWhere('car.horsepower = :horsepower')
                ->setParameter('horsepower', $horsepower);
        }

        if ($minHorsepower !== null) {
            $queryBuilder->andWhere('car.horsepower >= :minHorsepower')
                ->setParameter('minHorsepower', $minHorsepower);
        }

        if ($maxHorsepower !== null) {
            $queryBuilder->andWhere('car.horsepower <= :maxHorsepower')
                ->setParameter('maxHorsepower', $maxHorsepower);
        }

        $queryBuilder
            ->setParameter("brandName", "%" . $brandName . "%")
            ->setParameter("modelName", "%" . $modelName . "%")
            ->setParameter("country", "%" . $country . "%")
            ->setParameter("fuelType", "%" . $fuelType . "%")
            ->setParameter("transmission", "%" . $transmission . "%")
            ->setParameter("color", "%" . $color . "%")
            ->setFirstResult($itemsPerPage * ($page - 1))
            ->setMaxResults($itemsPerPage);

        return $queryBuilder->getQuery()->getResult();
    }
}

