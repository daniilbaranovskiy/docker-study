<?php

namespace App\Controller;

use App\Entity\Brand;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BrandController extends AbstractController
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
    #[Route('brand-add', name: 'brand_add')]
    public function add(Request $request): JsonResponse
    {
        $requestData = json_decode($request->getContent(), true);
        if (!isset(
            $requestData['name'],
        )) {
            throw new Exception("Invalid request data");
        }
        $brand = new Brand();
        $brand->setName($requestData['name']);
        $this->entityManager->persist($brand);
        $this->entityManager->flush();
        return new JsonResponse($brand, Response::HTTP_CREATED);
    }
    /**
     * @return JsonResponse
     */

    #[Route('brand-all', name: 'brand_all')]
    public function getAll(): JsonResponse
    {
        $brands = $this->entityManager->getRepository(Brand::class)->findAll();
        return new JsonResponse($brands);
    }

    /**
     * @return JsonResponse
     */
    #[Route('brand-name', name: 'brand_name')]
    public function getBrandByName(): JsonResponse
    {
        $brands = $this->entityManager->getRepository(Brand::class)->getAllBrandByName("AUDI");
        return new JsonResponse($brands);
    }
    /**
     * @param string $id
     * @return JsonResponse
     * @throws Exception
     */
    #[Route('brand/{id}', name: 'brand_get_item')]
    public function getItem(string $id): JsonResponse
    {
        /** @var Brand $brand */
        $brand = $this->entityManager->getRepository(Brand::class)->find($id);
        if (!$brand) {
            throw new Exception("Brand with id " . $id . " not found");
        }
        return new JsonResponse($brand);
    }

    /**
     * @param string $id
     * @return JsonResponse
     * @throws Exception
     */
    #[Route('brand-update/{id}', name: 'brand_update_item')]
    public function updateBrand(string $id): JsonResponse
    {
        /** @var Brand $brand */
        $brand = $this->entityManager->getRepository(Brand::class)->find($id);
        if (!$brand) {
            throw new Exception("Brand with id " . $id . " not found");
        }
        $brand->setName("New name");
        $this->entityManager->flush();
        return new JsonResponse($brand);
    }
    /**
     * @param string $id
     * @return JsonResponse
     * @throws Exception
     */
    #[Route('brand-delete/{id}', name: 'brand_delete_item')]
    public function deleteBrand(string $id): JsonResponse
    {
        /** @var Brand $brand */
        $brand = $this->entityManager->getRepository(Brand::class)->find($id);
        if (!$brand) {
            throw new Exception("Brand with id " . $id . " not found");
        }
        $this->entityManager->remove($brand);
        $this->entityManager->flush();
        return new JsonResponse();
    }

}
