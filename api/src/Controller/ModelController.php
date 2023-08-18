<?php

namespace App\Controller;

use App\Entity\Model;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ModelController extends AbstractController
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
    #[Route('model-add', name: 'model_add')]
    public function add(Request $request): JsonResponse
    {
        $requestData = json_decode($request->getContent(), true);
        if (!isset(
            $requestData['name'],
            $requestData['brand']
        )) {
            throw new Exception("Invalid request data");
        }
        $brand = $this->entityManager->getRepository(Model::class)->find($requestData["model"]);
        if (!$brand) {
            throw new Exception("Brand with id " . $requestData['brand'] . " not found");
        }
        $model = new Model();
        $model
            ->setName($requestData['year'])
            ->setBrand($brand);
        $this->entityManager->persist($model);
        $this->entityManager->flush();
        return new JsonResponse($model, Response::HTTP_CREATED);
    }

    /**
     * @return JsonResponse
     */

    #[Route('model-all', name: 'model_all')]
    public function getAll(): JsonResponse
    {
        $model = $this->entityManager->getRepository(Model::class)->findAll();
        return new JsonResponse($model);
    }

    /**
     * @return JsonResponse
     */
    #[Route('model-name', name: 'model_name')]
    public function getModelByName(): JsonResponse
    {
        $model = $this->entityManager->getRepository(Model::class)->getAllModelsByName("A4");
        return new JsonResponse($model);
    }

    /**
     * @param string $id
     * @return JsonResponse
     * @throws Exception
     */
    #[Route('model/{id}', name: 'model_get_item')]
    public function getItem(string $id): JsonResponse
    {
        /** @var Model $model */
        $model = $this->entityManager->getRepository(Model::class)->find($id);
        if (!$model) {
            throw new Exception("Model with id " . $id . " not found");
        }
        return new JsonResponse($model);
    }

    /**
     * @param string $id
     * @return JsonResponse
     * @throws Exception
     */
    #[Route('model-update/{id}', name: 'model_update_item')]
    public function updateModel(string $id): JsonResponse
    {
        /** @var Model $model */
        $model = $this->entityManager->getRepository(Model::class)->find($id);
        if (!$model) {
            throw new Exception("Model with id " . $id . " not found");
        }
        $model->setName("New name");
        $this->entityManager->flush();
        return new JsonResponse($model);
    }

    /**
     * @param string $id
     * @return JsonResponse
     * @throws Exception
     */
    #[Route('model-delete/{id}', name: 'model_delete_item')]
    public function deleteModel(string $id): JsonResponse
    {
        /** @var Model $model */
        $model = $this->entityManager->getRepository(Model::class)->find($id);
        if (!$model) {
            throw new Exception("Model with id " . $id . " not found");
        }
        $this->entityManager->remove($model);
        $this->entityManager->flush();
        return new JsonResponse();
    }

}
