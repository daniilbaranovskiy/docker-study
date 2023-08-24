<?php

namespace App\Controller;

use App\Entity\Car;
use App\Entity\Orders;
use App\Entity\User;
use DateTimeZone;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use DateTimeImmutable;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class OrdersController extends AbstractController
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
    #[Route('order-add', name: 'order_add')]
    public function addOrder(Request $request): JsonResponse
    {
        $requestData = json_decode($request->getContent(), true);
        if (!isset(
            $requestData['car_ids'],
            $requestData['user_id']
        )) {
            throw new Exception("Invalid request data");
        }

        $user = $this->entityManager->getRepository(User::class)->find($requestData["user_id"]);
        if (!$user) {
            throw new Exception("User with id " . $requestData['user_id'] . " not found");
        }

        $currentUser = $this->getUser();
        if ($currentUser !== $user || !in_array(User::ROLE_USER, $currentUser->getRoles())) {
            throw new AccessDeniedException('Unauthorized');
        }

        $timezone = new DateTimeZone('Europe/Kiev');
        $orderDate = new DateTimeImmutable('now', $timezone);
        $order = new Orders();
        $order
            ->setOrderDate($orderDate)
            ->setUser($user);
        $totalOrderSum = 0;
        foreach ($requestData['car_ids'] as $carId) {
            $car = $this->entityManager->getRepository(Car::class)->find($carId);
            if (!$car) {
                throw new Exception("Car with id " . $carId . " not found");
            }
            $order->addCar($car);
            $totalOrderSum += $car->getPrice();
        }
        $order->setOrderSum($totalOrderSum);
        $this->entityManager->persist($order);
        $this->entityManager->flush();

        return new JsonResponse($order, Response::HTTP_CREATED);
    }

    /**
     * @return JsonResponse
     */
    #[Route('order-all', name: 'order_all')]
    public function getAll(): JsonResponse
    {
        $this->checkAdminAuthorization();
        $order = $this->entityManager->getRepository(Orders::class)->findAll();

        return new JsonResponse($order);
    }

    /**
     * @param string $id
     * @return JsonResponse
     * @throws Exception
     */
    #[Route('order/{id}', name: 'order_get_item')]
    public function getOrder(string $id): JsonResponse
    {
        $currentUser = $this->getUser();
        /** @var Orders $order */
        $order = $this->entityManager->getRepository(Orders::class)->find($id);
        if (!$order) {
            throw new Exception("Order with id " . $id . " not found");
        }

        if ($currentUser !== $order->getUser()) {
            throw new AccessDeniedException('Unauthorized');
        }

        return new JsonResponse($order);
    }

    /**
     * @param string $id
     * @return JsonResponse
     * @throws Exception
     */
    #[Route('order-update/{id}', name: 'order_update_item')]
    public function updateOrder(string $id): JsonResponse
    {
        $currentUser = $this->getUser();
        /** @var Orders $order */
        $order = $this->entityManager->getRepository(Orders::class)->find($id);
        if (!$order) {
            throw new Exception("Order with id " . $id . " not found");
        }

        if ($currentUser !== $order->getUser()) {
            throw new AccessDeniedException('Unauthorized');
        }

        $order->setOrderSum(21000);
        $this->entityManager->flush();

        return new JsonResponse($order);
    }

    /**
     * @param string $id
     * @return JsonResponse
     * @throws Exception
     */
    #[Route('order-delete/{id}', name: 'order_delete_item')]
    public function deleteOrder(string $id): JsonResponse
    {
        $currentUser = $this->getUser();
        /** @var Orders $order */
        $order = $this->entityManager->getRepository(Orders::class)->find($id);
        if (!$order) {
            throw new Exception("Order with id " . $id . " not found");
        }

        if ($currentUser !== $order->getUser()) {
            throw new AccessDeniedException('Unauthorized');
        }

        $this->entityManager->remove($order);
        $this->entityManager->flush();

        return new JsonResponse();
    }

    /**
     * @return void
     */
    public function checkAdminAuthorization(): void
    {
        $user = $this->getUser();
        if (!$user || !in_array(User::ROLE_ADMIN, $user->getRoles())) {
            throw new AccessDeniedException('Unauthorized');
        }
    }


}