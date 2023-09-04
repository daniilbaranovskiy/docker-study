<?php

namespace App\Extensions;

use App\Entity\Product;

class ProductExtension extends UserRelationExtension
{
    /**
     * @return string
     */
    public function getResourceClass(): string
    {
        return Product::class;
    }


}