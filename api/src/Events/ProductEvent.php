<?php

namespace App\Events;

use App\Entity\Product;
use Symfony\Contracts\EventDispatcher\Event;

/**
 * Class ProductEvent
 * @package App\Events
 */
class ProductEvent extends Event
{

    public const PRODUCT_CREATE = 'product.create';

    /**
     * @var Product
     */
    private Product $product;


    /**
     * @param Product $product
     */
    public function __construct(Product $product)
    {
        $this->product = $product;
    }

    /**
     * @return Product
     */
    public function getProduct(): Product
    {
        return $this->product;
    }

}