<?php
namespace App\Interfaces;

interface ApiProductInterface {
    public function myProducts();
    public function all();
    public function create(array $data);
    public function update($id, array $data);
    public function delete($slugs);
    public function find($id);
    public function paginate(int $perPage);
}