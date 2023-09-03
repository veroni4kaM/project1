<?php

namespace App\Action;

use App\Entity\Product;

class UpdateProductAction
{
    /**
     * @param Product $data
     * @return Product
     */
    public function __invoke(Product $data): Product
    {
        return $data;
    }
}