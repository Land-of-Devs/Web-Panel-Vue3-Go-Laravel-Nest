<?php
namespace App\Interfaces;

interface ApiCrudInterface {


    public function all();
    
    public function paginate(int $perPage);

    public function create(array $data);

    public function delete($id);

    public function find($id);

    public function update($id,array $data);
}