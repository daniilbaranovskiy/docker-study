<?php

namespace App\Controller;

use App\Entity\Car;
use App\Entity\Customer;
use App\Entity\Order;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use DateTimeImmutable;

class OrderController extends AbstractController
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
    public function add(Request $request): JsonResponse
    {
        $requestData = json_decode($request->getContent(), true);
        if (!isset(
            $requestData['order_date'],
            $requestData['order_sum'],
            $requestData['car_id'],
            $requestData['customer_id']
        )) {
            throw new Exception("Invalid request data");
        }
        $car = $this->entityManager->getRepository(Car::class)->find($requestData["car_id"]);
        if (!$car) {
            throw new Exception("Car with id " . $requestData['car_id'] . " not found");
        }
        $customer = $this->entityManager->getRepository(Customer::class)->find($requestData["customer_id"]);
        if (!$customer) {
            throw new Exception("Customer with id " . $requestData['customer_id'] . " not found");
        }
        $orderDate = new DateTimeImmutable($requestData['order_date']);
        $order = new Order();
        $order
            ->setOrderDate($orderDate)
            ->setOrderSum($requestData['order_sum'])
            ->setCustomer($customer);
        $order->setCar($car);
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
        $order = $this->entityManager->getRepository(Order::class)->findAll();
        return new JsonResponse($order);
    }

    /**
     * @return JsonResponse
     */
    #[Route('order-customer', name: 'order_customer')]
    public function getOrdersByCustomerId(): JsonResponse
    {
        $orders = $this->entityManager->getRepository(Order::class)->getAllOrdersByCustomerId(1);
        return new JsonResponse($orders);
    }

    /**
     * @param string $id
     * @return JsonResponse
     * @throws Exception
     */
    #[Route('order/{id}', name: 'order_get_item')]
    public function getItem(string $id): JsonResponse
    {
        /** @var Order $order */
        $order = $this->entityManager->getRepository(Order::class)->find($id);
        if (!$order) {
            throw new Exception("Order with id " . $id . " not found");
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
        /** @var Order $order */
        $order = $this->entityManager->getRepository(Order::class)->find($id);
        if (!$order) {
            throw new Exception("Order with id " . $id . " not found");
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
        /** @var Order $order */
        $order = $this->entityManager->getRepository(Order::class)->find($id);
        if (!$order) {
            throw new Exception("Order with id " . $id . " not found");
        }
        $this->entityManager->remove($order);
        $this->entityManager->flush();
        return new JsonResponse();
    }

}
