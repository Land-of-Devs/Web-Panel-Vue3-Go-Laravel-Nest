<?php

namespace App\Domain\UseCases\Products;

use App\Domain\Interfaces\Products\ProductEntity;
use App\Domain\Interfaces\ViewModel;

interface ProductOutputPort
{
    public function product(ProductEntity $product): ViewModel;
    public function listProducts(object $products): ViewModel;
    public function success(string $msg, int $code, object $result): ViewModel;
    public function fail(string $msg, int $code): ViewModel;
}
