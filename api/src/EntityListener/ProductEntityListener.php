<?php

namespace App\EntityListener;

use App\Entity\Product;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Symfony\Component\Security\Core\Security;


class ProductEntityListener
{

    /**
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $entityManager;
    /**
     * @var Security
     */
    private Security $security;

    /**
     * @param EntityManagerInterface $entityManager
     * @param Security $security
     */
    public function __construct(EntityManagerInterface $entityManager, Security $security)
    {
        $this->entityManager = $entityManager;
        $this->security = $security;
    }

    /**
     * @param Product $product
     * @param LifecycleEventArgs $eventArgs
     * @return void
     */
    public function prePersist(Product $product, LifecycleEventArgs $eventArgs): void
    {
        $currentDate = new \DateTime();
        $timestampInMilliseconds = $currentDate->getTimestamp() * 1000;
        $product->setCreatedAt($timestampInMilliseconds);

        /** @var User $currentUser */
        $currentUser = $this->security->getUser();
        $user = $this->entityManager->getRepository(User::class)->find($currentUser->getId());

        $product->setUser($user);
    }
}