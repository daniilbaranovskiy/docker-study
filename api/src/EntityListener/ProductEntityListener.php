<?php

namespace App\EntityListener;

use App\Entity\Product;
use Doctrine\Persistence\Event\LifecycleEventArgs;

class ProductEntityListener
{
    /**
     * @param Product $product
     * @param LifecycleEventArgs $eventArgs
     * @return void
     */
    public function prePersist(Product $product, LifecycleEventArgs $eventArgs): void
    {
        $currentName = $product->getName();

        $newName = $currentName . '1';

        $product->setName($newName);
    }
}