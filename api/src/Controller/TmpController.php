<?php

namespace App\Controller;


use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;


class TmpController extends AbstractController
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
    public function __construct(EntityManagerInterface $entityManager, DenormalizerInterface $denormalizer, ValidatorInterface $validator)
    {
        $this->entityManager = $entityManager;
        $this->denormalizer = $denormalizer;
        $this->validator = $validator;
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    #[Route(path: "tmp", name: "app_tmp")]
    public function tmp(Request $request): JsonResponse
    {
        //$user = $this->getUser();
        $requestData = json_decode($request->getContent(), true);
        $products = $this->denormalizer->denormalize($requestData, Product::class, "array");
//        $products = $this->entityManager->getRepository(Product::class)->findAll();
//        if (in_array(User::ROLE_ADMIN, $user->getRoles())) {
//            return new JsonResponse($products);
//        }
        $errors = $this->validator->validate($products);
        if (count($errors) > 0) {
            return new JsonResponse((string)$errors);

        }
        return new JsonResponse();

    }

    /**
     * @param array $products
     * @return array
     */
    public function fetchProductsForUser(array $products): array
    {
        $fetchedProductsForUser = null;

        /** @var Product $product */
        foreach ($products as $product) {
            $tmpProductData = $product->jsonSerialize();

            unset($tmpProductData['description']);
            $fetchedProductsForUser[] = $tmpProductData;

        }
        return $fetchedProductsForUser;
    }
}
