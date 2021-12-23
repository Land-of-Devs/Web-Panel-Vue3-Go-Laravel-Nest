<?php

namespace App\Domain\UseCases\Products;

use App\Domain\Interfaces\ViewModel;

interface ProductInputPort
{
    public function myProducts(): ViewModel;
    public function all(): ViewModel;
    public function create(ProductRequestModel $request): ViewModel;
    public function update(string $slug, ProductRequestModel $request): ViewModel;
    public function delete(array $slugs): ViewModel;
    public function status(array $slugs, string $status): ViewModel;
}
