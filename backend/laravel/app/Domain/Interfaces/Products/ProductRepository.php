<?php

namespace App\Domain\Interfaces\Products;

use App\Domain\Interfaces\Products\ProductEntity;
use Illuminate\Http\UploadedFile as file;

interface ProductRepository
{
    public function create(ProductEntity $user, file $image ): ?ProductEntity;
    public function myProducts(): object;
    public function all(): object;
    public function update(string $slug, ProductEntity $user, file $image ): ?ProductEntity;
    public function delete(array $slugs): ?object;
    public function status(array $slugs, string $status): ?object;
}
