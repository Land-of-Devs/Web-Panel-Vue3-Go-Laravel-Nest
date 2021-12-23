<?php

namespace App\Factories;

use App\Domain\Interfaces\Products\ProductEntity;
use App\Domain\Interfaces\Products\ProductFactory;
use App\Models\Product;

class ProductModelFactory implements ProductFactory
{
  public function make(array $attribs = []): ProductEntity
  {
    return new Product($attribs);
  }
}
