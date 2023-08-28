<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class UserController extends AbstractController
{

    /**
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $entityManager;

    /**
     * @var UserPasswordHasherInterface
     */
    private UserPasswordHasherInterface $passwordHasher;

    /**
     * @var DenormalizerInterface
     */
    private DenormalizerInterface $denormalizer;

    /**
     * @var ValidatorInterface
     */
    private ValidatorInterface $validator;

    /**
     * @param UserPasswordHasherInterface $passwordHasher
     * @param EntityManagerInterface $entityManager
     * @param DenormalizerInterface $denormalizer
     * @param ValidatorInterface $validator
     */
    public function __construct(
        UserPasswordHasherInterface $passwordHasher,
        EntityManagerInterface      $entityManager,
        DenormalizerInterface       $denormalizer,
        ValidatorInterface          $validator
    )
    {
        $this->passwordHasher = $passwordHasher;
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
    #[Route('user', name: 'user_add', methods: ['POST'])]
    public function add(Request $request): JsonResponse
    {
        $user = $this->getUser();
        if ($user) {
            return new JsonResponse(['message' => 'You are already authorized']);
        }

        $requestData = json_decode($request->getContent(), true);
        $newUser = $this->denormalizer->denormalize($requestData, User::class, "array");
        $hashedPassword = $this->passwordHasher->hashPassword(
            $newUser,
            $requestData['password']
        );
        $newUser
            ->setEmail($requestData['email'])
            ->setPassword($hashedPassword);
        $errors = $this->validator->validate($newUser);
        if (count($errors) > 0) {
            return new JsonResponse((string)$errors);
        }

        $this->entityManager->persist($newUser);
        $this->entityManager->flush();

        return new JsonResponse($newUser, Response::HTTP_CREATED);
    }

    /**
     * @return JsonResponse
     */
    #[Route('user', name: 'user_list', methods: ['GET'])]
    public function list(): JsonResponse
    {
        $this->checkAdminAuthorization();
        $users = $this->entityManager->getRepository(User::class)->findAll();

        return new JsonResponse($users);
    }

    /**
     * @param string $id
     * @return JsonResponse
     * @throws Exception
     */
    #[Route('user/{id}', name: 'user_show', methods: ['GET'])]
    public function show(string $id): JsonResponse
    {
        /** @var User $currentUser */
        $currentUser = $this->getUser();
        /** @var User $user */
        $user = $this->entityManager->getRepository(User::class)->find($id);
        if (!$user) {
            throw new NotFoundHttpException("User with id " . $id . " not found");
        }

        if ($currentUser && ($currentUser->getId() === $user->getId() || in_array('ROLE_ADMIN', $currentUser->getRoles()))) {
            return new JsonResponse($user);
        } else {
            throw new AccessDeniedHttpException();
        }
    }

    /**
     * @param Request $request
     * @param string $id
     * @return JsonResponse
     */
    #[Route('user/{id}', name: 'user_update', methods: ['PUT'])]
    public function update(Request $request, string $id): JsonResponse
    {
        /** @var User $currentUser */
        $currentUser = $this->getUser();
        /** @var User $user */
        $user = $this->entityManager->getRepository(User::class)->find($id);
        if (!$user) {
            throw new NotFoundHttpException("User with id " . $id . " not found");
        }

        $requestData = json_decode($request->getContent(), true);
        if ($currentUser && $currentUser->getId() === $user->getId()) {
            $user->setEmail($requestData['email']);
            $errors = $this->validator->validate($user);
            if (count($errors) > 0) {
                return new JsonResponse((string)$errors);
            }

            $this->entityManager->flush();

            return new JsonResponse($user);
        } else {
            throw new AccessDeniedHttpException();
        }
    }

    /**
     * @param string $id
     * @return JsonResponse
     * @throws Exception
     */
    #[Route('user/{id}', name: 'user_delete', methods: ['DELETE'])]
    public function delete(string $id): JsonResponse
    {
        /** @var User $currentUser */
        $currentUser = $this->getUser();
        /** @var User $user */
        $user = $this->entityManager->getRepository(User::class)->find($id);
        if (!$user) {
            throw new NotFoundHttpException("User with id " . $id . " not found");
        }

        if ($currentUser && $currentUser->getId() === $user->getId()) {
            $this->entityManager->remove($user);
            $this->entityManager->flush();

            return new JsonResponse(status: Response::HTTP_NON_AUTHORITATIVE_INFORMATION);
        } else {
            throw new AccessDeniedHttpException();
        }
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