<?php

namespace App\Domain\Interfaces\Products;

interface ProductFactory
{
    public function make(array $attribs = []): ProductEntity;
}
