<?php

namespace App\Domain\UseCases\Products;

use App\Domain\Interfaces\Products\ProductFactory;
use App\Domain\Interfaces\Products\ProductRepository;
use App\Domain\Interfaces\ViewModel;
use App\Traits\RepositoryUtilsTrait;

class ProductInteractor implements ProductInputPort
{
    use RepositoryUtilsTrait;

    public function __construct(
        private ProductOutputPort $output,
        private ProductRepository $repository,
        private ProductFactory $factory
    ) {
    }

    public function myProducts(): ViewModel{
        $success = $this->repository->myProducts();
        if ($success) {
            return $this->output->listProducts($success);
        } else {
            return $this->output->fail('List of Products Not Found!!!', 404);
        }
    }

    public function all(): ViewModel
    {
        $success = $this->repository->all();
        if ($success) {
            return $this->output->listProducts($success);
        } else {
            return $this->output->fail('List of Products Not Found!!!', 404);
        }
    }

    public function create(ProductRequestModel $request): ViewModel
    {
        $success = $this->repository->create($this->factory->make([
            'name'          => $request->getName(),
            'description'   => $request->getDescription(),
            'price'         => $request->getPrice(),
        ]), $request->getImage());

        if ($success) {
            return $this->output->product($success);
        } else {
            return $this->output->fail('Creation Failed!!!', 400);
        }
    }

    public function update(string $slug, ProductRequestModel $request): ViewModel
    {
        $success = $this->repository->update($slug, $this->factory->make(self::cleanArray([
            'name'          => $request->getName(),
            'description'   => $request->getDescription(),
            'price'         => $request->getPrice(),
        ])), $request->getImage());

        if ($success) {
            return $this->output->product($success);
        } else {
            return $this->output->fail('Update Failed!!!', 400);
        }
    }

    public function delete(array $slugs): ViewModel
    {
        $success = $this->repository->delete($slugs);

        if ($success->result) {
            if($success->count > 0 ){
                return $this->output->success('Products were successfully deleted!!!', 300, $success);
            }else{
                return $this->output->success("Wasn't deleted any product!!", 300, $success);
            }
        } else {
            return $this->output->fail('Delete Failed!!!', 400);
        }
    }

    public function status(array $slugs, string $status): ViewModel
    {
        $success = $this->repository->status($slugs, $status);

        if ($success->result) {
            if ($success->count > 0) {
                return $this->output->success('Product status were successfully changed!!!', 300, $success);
            } else {
                return $this->output->success("Wasn't change any product status!!", 300, $success);
            }
        } else {
            return $this->output->fail('Prodcut status change Failed!!!', 400);
        }
    }

}
