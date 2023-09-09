<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
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
    #[Route('product-create', name: 'product_create')]
    public function create(Request $request): JsonResponse
    {
        $requestData = json_decode($request->getContent(), true);

        if (!isset(
            $requestData['price'],
            $requestData['name'],
            $requestData['description'],
            $requestData['category']
        )) {
            throw new Exception("Invalid request data");
        }

        $category = $this->entityManager->getRepository(Category::class)->find($requestData["category"]);

        if (!$category) {
            throw new Exception("Category with id " . $requestData['category'] . " not found");
        }

        $product = new Product();

        $product
            ->setPrice($requestData['price'])
            ->setName($requestData['name'])
            ->setDescription($requestData['description'])
            ->setCategory($category);

        $this->entityManager->persist($product);

        $this->entityManager->flush();

        return new JsonResponse($product, Response::HTTP_CREATED);
    }

    /**
     * @return JsonResponse
     */
    #[Route('product-all', name: 'product_all')]
    public function getAll(): JsonResponse
    {
        $products = $this->entityManager->getRepository(Product::class)->findAll();

        return new JsonResponse($products);
    }

    /**
     * @param string $id
     * @return JsonResponse
     * @throws Exception
     */
    #[Route('product/{id}', name: 'product_get_item')]
    public function getItem(string $id): JsonResponse
    {
        $product = $this->entityManager->getRepository(Product::class)->find($id);

        if (!$product) {
            throw new Exception("Product with id " . $id . " not found");
        }

        return new JsonResponse($product);
    }

    /**
     * @param string $id
     * @return JsonResponse
     * @throws Exception
     */
    #[Route('product-update/{id}', name: 'product_update_item')]
    public function updateProduct(string $id): JsonResponse
    {
        /** @var Product $product */
        $product = $this->entityManager->getRepository(Product::class)->find($id);

        if (!$product) {
            throw new Exception("Product with id " . $id . " not found");
        }

        $product->setName("New name");

        $this->entityManager->flush();

        return new JsonResponse($product);
    }

    /**
     * @param string $id
     * @return JsonResponse
     * @throws Exception
     */
    #[Route('product-delete/{id}', name: 'product_delete_item')]
    public function deleteProduct(string $id): JsonResponse
    {
        /** @var Product $product */
        $product = $this->entityManager->getRepository(Product::class)->find($id);

        if (!$product) {
            throw new Exception("Product with id " . $id . " not found");
        }
        $this->entityManager->remove($product);
        $this->entityManager->flush();
        return new JsonResponse();
    }

}