<?php

namespace App\Domain\UseCases\Products;

use \Illuminate\Http\UploadedFile as file;
class ProductRequestModel
{
    public function __construct(
        private array $attributes,
    ) {
    }
    
    public function getName(): ?string
    {
        return $this->attributes['name'] ?? null;
    }

    public function getDescription(): ?string
    {
        return $this->attributes['description'] ?? null;
    }

    public function getPrice(): ?int
    {
        return $this->attributes['price'] ?? null;
    }

    public function getImage(): ?file
    {
        return $this->attributes['image'] ?? null;
    }
}
