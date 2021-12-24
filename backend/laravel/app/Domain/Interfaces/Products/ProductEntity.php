<?php

namespace App\Domain\Interfaces\Products;

interface ProductEntity
{
    public function getId():int;
    public function getCreator(): ?string;
    public function setCreator(string $creator);
    public function getName(): ?string;
    public function setName(string $name);
    public function getImage(): ?string;
    public function setImage(string $image);
    public function getSlug(): ?string;
    public function setSlug(string $slug);
    public function setStatus(string $status);
    public function saveProduct(): void;
    public function deleteProduct(): void;
    public function fillProduct(array $attr): void;
}
