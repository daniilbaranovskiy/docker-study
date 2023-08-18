<?php

namespace App\Controller;

use App\Entity\Car;
use App\Entity\Customer;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CustomerController extends AbstractController
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
    #[Route('customer-add', name: 'customer_add')]
    public function add(Request $request): JsonResponse
    {
        $requestData = json_decode($request->getContent(), true);
        if (!isset(
            $requestData['firstname'],
            $requestData['lastname'],
            $requestData['address'],
            $requestData['number'],
            $requestData['email'],
        )) {
            throw new Exception("Invalid request data");
        }
        $customer = new Customer();
        $customer
            ->setFirstname($requestData['firstname'])
            ->setLastname($requestData['lastname'])
            ->setAddress($requestData['address'])
            ->setNumber($requestData['number'])
            ->setEmail($requestData['email']);
        $this->entityManager->persist($customer);
        $this->entityManager->flush();
        return new JsonResponse($customer, Response::HTTP_CREATED);
    }
    /**
     * @return JsonResponse
     */

    #[Route('customer-all', name: 'customer_all')]
    public function getAll(): JsonResponse
    {
        $customers = $this->entityManager->getRepository(Customer::class)->findAll();
        return new JsonResponse($customers);
    }

    /**
     * @return JsonResponse
     */
    #[Route('customer-lastname', name: 'customer_lastname')]
    public function getCustomerByLastName(): JsonResponse
    {
        $customers = $this->entityManager->getRepository(Customer::class)->getAllCustomersByLastName("test2");
        return new JsonResponse($customers);
    }

    /**
     * @param string $id
     * @return JsonResponse
     * @throws Exception
     */
    #[Route('customer/{id}', name: 'customer_get_item')]
    public function getItem(string $id): JsonResponse
    {
        /** @var Customer $customer */
        $customer = $this->entityManager->getRepository(Customer::class)->find($id);
        if (!$customer) {
            throw new Exception("Customer with id " . $id . " not found");
        }
        return new JsonResponse($customer);
    }

    /**
     * @param string $id
     * @return JsonResponse
     * @throws Exception
     */
    #[Route('customer-update/{id}', name: 'customer_update_item')]
    public function updateCustomer(string $id): JsonResponse
    {
        /** @var Customer $customer */
        $customer = $this->entityManager->getRepository(Customer::class)->find($id);
        if (!$customer) {
            throw new Exception("Customer with id " . $id . " not found");
        }
        $customer->setFirstname("New name");
        $this->entityManager->flush();
        return new JsonResponse($customer);
    }
    /**
     * @param string $id
     * @return JsonResponse
     * @throws Exception
     */
    #[Route('customer-delete/{id}', name: 'customer_delete_item')]
    public function deleteCustomer(string $id): JsonResponse
    {
        /** @var Customer $customer */
        $customer = $this->entityManager->getRepository(Customer::class)->find($id);
        if (!$customer) {
            throw new Exception("Customer with id " . $id . " not found");
        }
        $this->entityManager->remove($customer);
        $this->entityManager->flush();
        return new JsonResponse();
    }

}
