<?php

namespace App\Transformers;

use App\Models\Product;

class ProductTransformer extends Transformer
{
    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(Product $product)
    {
        return $product->getAttributes();
    }
}
