<?php

namespace App\Controller;

use App\Entity\Model;
use App\Entity\User;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

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
    public function addModel(Request $request): JsonResponse
    {
        $this->checkAdminAuthorization();
        $requestData = json_decode($request->getContent(), true);
        if (!isset(
            $requestData['brand'],
            $requestData['name'],
            $requestData['country'],
            $requestData['releaseDate']
        )) {
            throw new Exception("Invalid request data");
        }

        $releaseDate = DateTime::createFromFormat('Y-m-d', $requestData['releaseDate']);
        if (!$releaseDate) {
            throw new Exception("Invalid release date format");
        }

        $model = new Model();
        $model
            ->setBrand($requestData['brand'])
            ->setName($requestData['name'])
            ->setCountry($requestData['country'])
            ->setReleaseDate($releaseDate);
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
        $this->checkAdminAuthorization();
        $model = $this->entityManager->getRepository(Model::class)->findAll();

        return new JsonResponse($model);
    }

    /**
     * @param string $id
     * @return JsonResponse
     * @throws Exception
     */
    #[Route('model/{id}', name: 'get_model')]
    public function getModel(string $id): JsonResponse
    {
        $this->checkAdminAuthorization();
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
    #[Route('model-update/{id}', name: 'model_update')]
    public function updateModel(string $id): JsonResponse
    {
        $this->checkAdminAuthorization();
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
    #[Route('model-delete/{id}', name: 'model_delete')]
    public function deleteModel(string $id): JsonResponse
    {
        $this->checkAdminAuthorization();
        /** @var Model $model */
        $model = $this->entityManager->getRepository(Model::class)->find($id);
        if (!$model) {
            throw new Exception("Model with id " . $id . " not found");
        }
        $this->entityManager->remove($model);
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