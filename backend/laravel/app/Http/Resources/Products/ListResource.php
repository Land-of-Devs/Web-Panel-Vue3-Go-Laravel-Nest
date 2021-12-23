<?php

namespace App\Http\Resources\Products;

use Illuminate\Http\Resources\Json\JsonResource;

class ListResource extends JsonResource
{
    public function __construct(object $products)
    {
        $this->resource = [
            'list' => $products
        ];
    }
}
