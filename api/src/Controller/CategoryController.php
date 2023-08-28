<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\User;
use DateTimeImmutable;
use DateTimeZone;
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

class CategoryController extends AbstractController
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
    #[Route('/category', name: 'category_add', methods: ['POST'])]
    public function add(Request $request): JsonResponse
    {
        $this->checkAdminAuthorization();

        $requestData = json_decode($request->getContent(), true);

        $category = $this->denormalizer->denormalize($requestData, Category::class, "array");

        $category
            ->setCreatedAt(new DateTimeImmutable('now', new DateTimeZone('Europe/Kiev')))
            ->setUpdatedAt(new DateTimeImmutable('now', new DateTimeZone('Europe/Kiev')));

        $errors = $this->validator->validate($category);

        if (count($errors) > 0) {
            return new JsonResponse((string)$errors);
        }

        $category
            ->setName($requestData['name'])
            ->setDescription($requestData['description']);

        $this->entityManager->persist($category);

        $this->entityManager->flush();

        return new JsonResponse($category, Response::HTTP_CREATED);
    }

    /**
     * @return JsonResponse
     */
    #[Route('category', name: 'category_list', methods: ['GET'])]
    public function list(): JsonResponse
    {
        $category = $this->entityManager->getRepository(Category::class)->findAll();

        return new JsonResponse($category);
    }

    /**
     * @param string $id
     * @return JsonResponse
     * @throws Exception
     */
    #[Route('category/{id}', name: 'category_show', methods: ['GET'])]
    public function show(string $id): JsonResponse
    {
        $category = $this->entityManager->getRepository(Category::class)->find($id);

        if (!$category) {
            throw new NotFoundHttpException("Category with id " . $id . " not found");
        }

        return new JsonResponse($category);
    }

    /**
     * @param Request $request
     * @param string $id
     * @return JsonResponse
     * @throws Exception
     */
    #[Route('category/{id}', name: 'category_update', methods: ['PUT'])]
    public function update(Request $request, string $id): JsonResponse
    {
        $this->checkAdminAuthorization();

        $requestData = json_decode($request->getContent(), true);

        $category = $this->entityManager->getRepository(Category::class)->find($id);

        if (!$category) {
            throw new NotFoundHttpException("Category with id " . $id . " not found");
        }

        $category
            ->setName($requestData['name'] ?? $category->getName())
            ->setDescription($requestData['description'] ?? $category->getDescription())
            ->setUpdatedAt(new DateTimeImmutable('now', new DateTimeZone('Europe/Kiev')));

        $errors = $this->validator->validate($category);

        if (count($errors) > 0) {
            return new JsonResponse((string)$errors);
        }

        $this->entityManager->flush();

        return new JsonResponse($category);
    }

    /**
     * @param string $id
     * @return JsonResponse
     * @throws Exception
     */
    #[Route('category/{id}', name: 'category_delete', methods: ['DELETE'])]
    public function delete(string $id): JsonResponse
    {
        $this->checkAdminAuthorization();

        /** @var Category $category */
        $category = $this->entityManager->getRepository(Category::class)->find($id);

        if (!$category) {
            throw new NotFoundHttpException("Category with id " . $id . " not found");
        }

        $this->entityManager->remove($category);

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