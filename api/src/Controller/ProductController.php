<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Product;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ProductController extends AbstractController
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
    #[Route('/product', name: 'product_add', methods: ['POST'])]
    public function add(Request $request): JsonResponse
    {
        $this->checkAdminAuthorization();
        $requestData = json_decode($request->getContent(), true);
        $category = $this->entityManager->getRepository(Category::class)->find($requestData["category"]);
        if (!$category) {
            throw new NotFoundHttpException("Category with id " . $requestData['category'] . " not found");
        }

        $product = $this->denormalizer->denormalize($requestData, Product::class, "array");
        $errors = $this->validator->validate($product);
        if (count($errors) > 0) {
            return new JsonResponse((string)$errors);
        }

        $product
            ->setName($requestData['name'])
            ->setPrice($requestData['price'])
            ->setDescription($requestData['description'])
            ->setQuantity($requestData['quantity'])
            ->setColor($requestData['color'])
            ->setCategory($category);
        $this->entityManager->persist($product);
        $this->entityManager->flush();

        return new JsonResponse($product, Response::HTTP_CREATED);
    }

    /**
     * @return JsonResponse
     */
    #[Route('product', name: 'product_list', methods: ['GET'])]
    public function list(): JsonResponse
    {
        $products = $this->entityManager->getRepository(Product::class)->findAll();

        return new JsonResponse($products);
    }

    /**
     * @param string $id
     * @return JsonResponse
     * @throws Exception
     */
    #[Route('product/{id}', name: 'product_show', methods: ['GET'])]
    public function show(string $id): JsonResponse
    {
        $product = $this->entityManager->getRepository(Product::class)->find($id);
        if (!$product) {
            throw new NotFoundHttpException("Product with id " . $id . " not found");
        }

        return new JsonResponse($product);
    }

    /**
     * @param Request $request
     * @param string $id
     * @return JsonResponse
     */
    #[Route('product/{id}', name: 'product_update', methods: ['PUT'])]
    public function update(Request $request, string $id): JsonResponse
    {
        $this->checkAdminAuthorization();
        /** @var Product $product */
        $requestData = json_decode($request->getContent(), true);
        $product = $this->entityManager->getRepository(Product::class)->find($id);
        if (!$product) {
            throw new NotFoundHttpException("Product with id " . $id . " not found");
        }

        $category = $this->entityManager->getRepository(Category::class)->find($requestData["category"]);
        if (!$category) {
            throw new NotFoundHttpException("Category with id " . $id . " not found");
        }

        $product
            ->setName($requestData['name'] ?? $product->getName())
            ->setPrice($requestData['price'] ?? $product->getPrice())
            ->setDescription($requestData['description'] ?? $product->getDescription())
            ->setQuantity($requestData['quantity'] ?? $product->getQuantity())
            ->setColor($requestData['color'] ?? $product->getColor())
            ->setCategory($category);
        $errors = $this->validator->validate($product);
        if (count($errors) > 0) {
            return new JsonResponse((string)$errors);
        }

        $this->entityManager->flush();

        return new JsonResponse($product);
    }

    /**
     * @param string $id
     * @return JsonResponse
     * @throws Exception
     */
    #[Route('product/{id}', name: 'product_delete', methods: ['DELETE'])]
    public function delete(string $id): JsonResponse
    {
        $this->checkAdminAuthorization();
        /** @var Product $product */
        $product = $this->entityManager->getRepository(Product::class)->find($id);
        if (!$product) {
            throw new NotFoundHttpException("Product with id " . $id . " not found");
        }

        $this->entityManager->remove($product);
        $this->entityManager->flush();

        return new JsonResponse(status: Response::HTTP_NON_AUTHORITATIVE_INFORMATION);
    }

    /**
     * @return void
     */
    public function checkAdminAuthorization(): void
    {
        $user = $this->getUser();
        if (!$user || !in_array(User::ROLE_ADMIN, $user->getRoles())) {
            throw new AccessDeniedHttpException("Access denied");
        }
    }

}