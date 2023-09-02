<?php

namespace App\EntityListener;

use App\Entity\Product;
use Doctrine\ORM\Event\PostPersistEventArgs;
use Doctrine\Persistence\Event\LifecycleEventArgs;

class ProductEntityListener
{
    /**
     * @param Product $product
     * @param PostPersistEventArgs $eventArgs
     * @return void
     */
    public function postPersist(Product $product, PostPersistEventArgs $eventArgs): void
    {
        $test = 1;
    }

    /**
     * @param Product $product
     * @param LifecycleEventArgs $eventArgs
     * @return void
     */
    public function postUpdate(Product $product, LifecycleEventArgs $eventArgs): void
    {
        $test = 1;
    }

    /**
     * @param Product $product
     * @param LifecycleEventArgs $eventArgs
     * @return void
     */
    public function preUpdate(Product $product, LifecycleEventArgs $eventArgs): void
    {
        $test = 1;
    }

    /**
     * @param Product $product
     * @param LifecycleEventArgs $eventArgs
     * @return void
     */
    public function prePersist(Product $product, LifecycleEventArgs $eventArgs): void
    {
        $test = 1;
    }
}