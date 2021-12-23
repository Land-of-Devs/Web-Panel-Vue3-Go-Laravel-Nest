<?php

namespace App\Http\Resources\Products;

use App\Domain\Interfaces\Products\ProductEntity;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    public function __construct(ProductEntity $product)
    {
        $this->resource = [
            'product' => $product 
        ];
    }
}
