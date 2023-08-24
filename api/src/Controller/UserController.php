<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{

    /**
     * @var UserPasswordHasherInterface
     */
    private UserPasswordHasherInterface $passwordHasher;
    /**
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $entityManager;

    /**
     * @param UserPasswordHasherInterface $passwordHasher
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(UserPasswordHasherInterface $passwordHasher, EntityManagerInterface $entityManager)
    {

        $this->passwordHasher = $passwordHasher;
        $this->entityManager = $entityManager;
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws Exception
     */
    #[Route('user-add', name: 'user_add')]
    public function addUser(Request $request): JsonResponse
    {
        $requestData = json_decode($request->getContent(), true);
        if (!isset(
            $requestData['email'],
            $requestData['password'],
        )) {
            throw new Exception("Invalid request data");
        }
        $user = new User();
        $hashedPassword = $this->passwordHasher->hashPassword(
            $user,
            $requestData['password']
        );
        $user
            ->setEmail($requestData['email'])
            ->setPassword($hashedPassword);
        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return new JsonResponse($user, Response::HTTP_CREATED);
    }

    /**
     * @return JsonResponse
     */
    #[Route('user-all', name: 'user_all')]
    public function getAll(): JsonResponse
    {
        $model = $this->entityManager->getRepository(User::class)->findAll();

        return new JsonResponse($model);
    }

    /**
     * @param string $id
     * @return JsonResponse
     * @throws Exception
     */
    #[Route('user/{id}', name: 'get_user')]
    public function getUserById(string $id): JsonResponse
    {
        /** @var User $user */
        $user = $this->entityManager->getRepository(User::class)->find($id);
        if (!$user) {
            throw new Exception("User with id " . $id . " not found");
        }

        return new JsonResponse($user);
    }

    /**
     * @param string $id
     * @return JsonResponse
     * @throws Exception
     */
    #[Route('user-update/{id}', name: 'user_update')]
    public function updateUser(string $id): JsonResponse
    {
        /** @var User $user */
        $user = $this->entityManager->getRepository(User::class)->find($id);
        if (!$user) {
            throw new Exception("User with id " . $id . " not found");
        }

        $user->setEmail("new@gmail.com");
        $this->entityManager->flush();

        return new JsonResponse($user);
    }

    /**
     * @param string $id
     * @return JsonResponse
     * @throws Exception
     */
    #[Route('user-delete/{id}', name: 'user_delete')]
    public function deleteUser(string $id): JsonResponse
    {
        /** @var User $user */
        $user = $this->entityManager->getRepository(User::class)->find($id);
        if (!$user) {
            throw new Exception("User with id " . $id . " not found");
        }

        $this->entityManager->remove($user);
        $this->entityManager->flush();

        return new JsonResponse();
    }
}