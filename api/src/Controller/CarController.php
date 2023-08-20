<?php

namespace App\Controller;

use App\Entity\Car;
use App\Entity\Model;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CarController extends AbstractController
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
     * @param Request $request
     * @return JsonResponse
     * @throws Exception
     */
    #[Route('car-add', name: 'car_add')]
    public function addCar(Request $request): JsonResponse
    {
        $requestData = json_decode($request->getContent(), true);
        if (!isset(
            $requestData['model'],
            $requestData['year'],
            $requestData['price'],
            $requestData['quantity'],
            $requestData['fuel_type'],
            $requestData['transmission'],
            $requestData['color'],
            $requestData['horsepower']
        )) {
            throw new Exception("Invalid request data");
        }
        $model = $this->entityManager->getRepository(Model::class)->find($requestData["model"]);
        if (!$model) {
            throw new Exception("Model with id " . $requestData['model'] . " not found");
        }
        $car = new Car();
        $car
            ->setYear($requestData['year'])
            ->setPrice($requestData['price'])
            ->setQuantity($requestData['quantity'])
            ->setFuelType($requestData['fuel_type'])
            ->setTransmission($requestData['transmission'])
            ->setColor($requestData['color'])
            ->setHorsepower($requestData['horsepower'])
            ->setModel($model);
        $this->entityManager->persist($car);
        $this->entityManager->flush();

        return new JsonResponse($car, Response::HTTP_CREATED);
    }

    /**
     * @return JsonResponse
     */
    #[Route('car-all', name: 'car_all')]
    public function getAll(): JsonResponse
    {
        $cars = $this->entityManager->getRepository(Car::class)->findAll();

        return new JsonResponse($cars);
    }

    #[Route(path: "cars", name: "filter_cars")]
    public function cars(Request $request): JsonResponse
    {
        $requestData = $request->query->all();
        $cars = $this->entityManager->getRepository(Car::class)->getAllCarsWithFilters(
            $requestData['itemsPerPage'] ?? 10,
            $requestData['page'] ?? 1,
            $requestData['brandName'] ?? null,
            $requestData['modelName'] ?? null,
            $requestData['country'] ?? null,
            $requestData['year'] ?? null,
            $requestData['minYear'] ?? null,
            $requestData['maxYear'] ?? null,
            $requestData['price'] ?? null,
            $requestData['minPrice'] ?? null,
            $requestData['maxPrice'] ?? null,
            $requestData['quantity'] ?? null,
            $requestData['minQuantity'] ?? null,
            $requestData['maxQuantity'] ?? null,
            $requestData['fuelType'] ?? null,
            $requestData['transmission'] ?? null,
            $requestData['color'] ?? null,
            $requestData['horsepower'] ?? null,
            $requestData['minHorsepower'] ?? null,
            $requestData['maxHorsepower'] ?? null
        );

        return new JsonResponse($cars);
    }

    /**
     * @param string $id
     * @return JsonResponse
     * @throws Exception
     */
    #[Route('car/{id}', name: 'get_car')]
    public function getCar(string $id): JsonResponse
    {
        /** @var Car $car */
        $car = $this->entityManager->getRepository(Car::class)->find($id);
        if (!$car) {
            throw new Exception("Car with id " . $id . " not found");
        }

        return new JsonResponse($car);
    }

    /**
     * @param string $id
     * @return JsonResponse
     * @throws Exception
     */
    #[Route('car-update/{id}', name: 'car_update_item')]
    public function updateCar(string $id): JsonResponse
    {
        /** @var Car $car */
        $car = $this->entityManager->getRepository(Car::class)->find($id);
        if (!$car) {
            throw new Exception("Car with id " . $id . " not found");
        }
        $car->setColor("New color");
        $this->entityManager->flush();

        return new JsonResponse($car);
    }

    /**
     * @param string $id
     * @return JsonResponse
     * @throws Exception
     */
    #[Route('car-delete/{id}', name: 'car_delete_item')]
    public function deleteCar(string $id): JsonResponse
    {
        /** @var Car $car */
        $car = $this->entityManager->getRepository(Car::class)->find($id);
        if (!$car) {
            throw new Exception("Car with id " . $id . " not found");
        }
        $this->entityManager->remove($car);
        $this->entityManager->flush();

        return new JsonResponse();
    }

}