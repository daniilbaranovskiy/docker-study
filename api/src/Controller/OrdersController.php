<?php

namespace App\Controller;

use App\Entity\Orders;
use App\Entity\Product;
use App\Entity\User;
use DateTimeZone;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use DateTimeImmutable;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class OrdersController extends AbstractController
{

    /**
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $entityManager;

    /**
     * @var DenormalizerInterface
     */
    private DenormalizerInterface $denormalizer;

    /**
     * @var ValidatorInterface
     */
    private ValidatorInterface $validator;

    /**
     * @param EntityManagerInterface $entityManager
     * @param DenormalizerInterface $denormalizer
     * @param ValidatorInterface $validator
     */
    public function __construct(
        EntityManagerInterface $entityManager,
        DenormalizerInterface  $denormalizer,
        ValidatorInterface     $validator
    )
    {
        $this->entityManager = $entityManager;
        $this->denormalizer = $denormalizer;
        $this->validator = $validator;
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws ExceptionInterface
     * @throws Exception
     */
    #[Route('order', name: 'order_add', methods: ['POST'])]
    public function add(Request $request): JsonResponse
    {
        $requestData = json_decode($request->getContent(), true);

        $currentUser = $this->getUser();
        /** @var User $currentUser */
        $user = $this->entityManager->getRepository(User::class)->find($currentUser->getId());
        if (!$user) {
            throw new NotFoundHttpException("User with id " . $requestData['user_id'] . " not found");
        }

        if ($currentUser !== $user || !in_array(User::ROLE_USER, $currentUser->getRoles())) {
            throw new AccessDeniedException();
        }

        $order = $this->denormalizer->denormalize($requestData, Orders::class, "array");

        $totalOrderSum = 0;
        $productRepository = $this->entityManager->getRepository(Product::class);
        $products = $productRepository->findBy(['id' => $requestData['product_ids']]);
        foreach ($products as $product) {
            if (!$product) {
                throw new NotFoundHttpException("Product not found");
            }

            $order->addProduct($product);
            $totalOrderSum += $product->getPrice();
        }
        $order
            ->setOrderDate(new DateTimeImmutable('now', new DateTimeZone('Europe/Kiev')))
            ->setOrderSum($totalOrderSum)
            ->setUser($user);
        $errors = $this->validator->validate($order);
        if (count($errors) > 0) {
            return new JsonResponse((string)$errors);
        }
        $order
            ->setStatus($requestData['status']);
        $this->entityManager->persist($order);
        $this->entityManager->flush();

        return new JsonResponse($order, Response::HTTP_CREATED);
    }


    /**
     * @return JsonResponse
     */
    #[Route('order', name: 'order_list', methods: ['GET'])]
    public function list(): JsonResponse
    {
        /** @var User $currentUser */
        $currentUser = $this->getUser();
        $orderRepository = $this->entityManager->getRepository(Orders::class);
        $orders = $orderRepository->findBy(['user' => $currentUser]);
        $user = $this->entityManager->getRepository(User::class)->find($currentUser->getId());
        if ($currentUser !== $user || !in_array(User::ROLE_USER, $currentUser->getRoles())) {
            $orders = $orderRepository->findAll();
            return new JsonResponse($orders);
        }

        return new JsonResponse($orders);
    }

    /**
     * @param string $id
     * @return JsonResponse
     * @throws Exception
     */
    #[Route('order/{id}', name: 'order_show', methods: ['GET'])]
    public function show(string $id): JsonResponse
    {
        $currentUser = $this->getUser();
        /** @var Orders $order */
        $order = $this->entityManager->getRepository(Orders::class)->find($id);
        if (!$order) {
            throw new NotFoundHttpException("Order with id " . $id . " not found");
        }

        if ($currentUser !== $order->getUser() && !in_array(User::ROLE_ADMIN, $currentUser->getRoles())) {
            throw new NotFoundHttpException();
        }

        return new JsonResponse($order);
    }

    /**
     * @param Request $request
     * @param string $id
     * @return JsonResponse
     * @throws Exception
     */
    #[Route('order/{id}', name: 'order_update', methods: ['PUT'])]
    public function update(Request $request, string $id): JsonResponse
    {
        $currentUser = $this->getUser();
        $requestData = json_decode($request->getContent(), true);
        /** @var Orders $order */
        $order = $this->entityManager->getRepository(Orders::class)->find($id);
        if (!$order) {
            throw new NotFoundHttpException("Order with id " . $id . " not found");
        }

        /** @var User $currentUser */
        $user = $this->entityManager->getRepository(User::class)->find($currentUser->getId());
        if ($currentUser !== $user || !in_array(User::ROLE_USER, $currentUser->getRoles())) {
            throw new AccessDeniedException();
        }

        $existingProducts = $order->getProducts();
        foreach ($existingProducts as $existingProduct) {
            $order->removeProduct($existingProduct);
        }
        $totalOrderSum = 0;
        $productRepository = $this->entityManager->getRepository(Product::class);
        $products = $productRepository->findBy(['id' => $requestData['product_ids']]);
        foreach ($products as $product) {
            if (!$product) {
                throw new NotFoundHttpException("Product not found");
            }

            $order->addProduct($product);
            $totalOrderSum += $product->getPrice();
        }
        $order
            ->setOrderDate(new DateTimeImmutable('now', new DateTimeZone('Europe/Kiev')))
            ->setOrderSum($totalOrderSum ?? $order->getOrderSum())
            ->setStatus($requestData['status'] ?? $order->getStatus());
        $errors = $this->validator->validate($order);
        if (count($errors) > 0) {
            return new JsonResponse((string)$errors);
        }
        $this->entityManager->flush();

        return new JsonResponse($order);
    }

    /**
     * @param string $id
     * @return JsonResponse
     * @throws Exception
     */
    #[Route('order/{id}', name: 'order_delete', methods: ['DELETE'])]
    public function delete(string $id): JsonResponse
    {
        $currentUser = $this->getUser();
        /** @var Orders $order */
        $order = $this->entityManager->getRepository(Orders::class)->find($id);
        if (!$order) {
            throw new NotFoundHttpException("Order with id " . $id . " not found");
        }

        if ($currentUser !== $order->getUser()) {
            throw new NotFoundHttpException();
        }

        $this->entityManager->remove($order);
        $this->entityManager->flush();

        return new JsonResponse(status: Response::HTTP_NON_AUTHORITATIVE_INFORMATION);
    }
}