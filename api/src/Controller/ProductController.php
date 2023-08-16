<?php

namespace App\Controller;

use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
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

    #[Route('/product-create', name: 'product_create')]
    public function create(Request $request): JsonResponse
    {
        $requestData = json_decode($request->getContent(), true);
        if (!isset($requestData['price'], $requestData['name'], $requestData['description'])) {
            throw new Exception('Error');
        }
        $product = new Product();
        $product->setPrice($requestData['price']);
        $product->setName($requestData['name']);
        $product->setDescription($requestData['description']);
        $this->entityManager->persist($product);
        $this->entityManager->flush();
        return new JsonResponse();
    }

    #[Route('/product-read', name: 'product_read')]
    public function read(Request $request): JsonResponse
    {
        $products = $this->entityManager->getRepository(Product::class)->findAll();
        return new JsonResponse($products);
    }

    /**
     * @param string $id
     * @return JsonResponse
     */
    #[Route('product/{id}', name: 'product_get_item')]
    public function getItem(string $id): JsonResponse
    {
        $product = $this->entityManager->getRepository(Product::class)->find($id);
        if (!$product) {
            throw new Exception('No product with id'. $id);
        }
        return new JsonResponse($product);
    }

    #[Route('product-update/{id}', name: 'product_update_item')]
    public function updateProduct(string $id): JsonResponse
    {
        /** @var Product $product */
        $product = $this->entityManager->getRepository(Product::class)->find($id);
        if (!$product) {
            throw new Exception('No product with id'. $id);
        }
        $product->setName("New Name");
        $this->entityManager->flush();
        return new JsonResponse($product);
    }

    #[Route('product-delete/{id}', name: 'product_delete_item')]
    public function deleteProduct(string $id): JsonResponse
    {
        /** @var Product $product */
        $product = $this->entityManager->getRepository(Product::class)->find($id);
        if (!$product) {
            throw new Exception('No product with id'. $id);
        }
        $this->entityManager->remove($product);
        $this->entityManager->flush();
        return new JsonResponse($product);
    }
}
