<?php

namespace App\Http\Controllers\Api\Products;

use App\Adapters\ViewModels\JsonResourceViewModel;
use App\Domain\UseCases\Products\ProductInputPort;
use App\Domain\UseCases\Products\ProductRequestModel;
use App\Http\Requests\Products\ProductCreateRequest;
use App\Http\Requests\Products\ProductUpdateRequest;
use App\Http\Requests\Products\ProductSlugsRequest;
use App\Http\Controllers\Controller;

class ProductController extends Controller
{

    public function __construct(
        private ProductInputPort $interactor,
    ) {}

    public function index()
    {
        $viewModel = $this->interactor->myProducts();
        
        if ($viewModel instanceof JsonResourceViewModel) {
            return $viewModel->getResource();
        }

        return null;
    }

    public function all()
    {
        $viewModel = $this->interactor->all();

        if ($viewModel instanceof JsonResourceViewModel) {
            return $viewModel->getResource();
        }

        return null;
    }

    // public function show($id)
    // {
    //     try {
    //         $data = $this->productRepository->find($id);
    //         if (is_null($data)) {
    //             $msg = 'Product Not Found';
    //             return self::apiResponseError(null, $msg, $this->not_found);
    //         }
    //         return self::apiResponseSuccess($data, 'See product details!');
    //     } catch (\Exception $e) {
    //         return self::apiServerError($e->getMessage());
    //     }
    // }

    public function store(ProductCreateRequest $request)
    {
        $viewModel = $this->interactor->create(
            new ProductRequestModel($request->validated())
        );

        if ($viewModel instanceof JsonResourceViewModel) {
            return $viewModel->getResource();
        }

        return null;
    }


    public function update(string $slug, ProductUpdateRequest $request)
    {
        $viewModel = $this->interactor->update($slug,
            new ProductRequestModel($request->validated())
        );

        if ($viewModel instanceof JsonResourceViewModel) {
            return $viewModel->getResource();
        }

        return null;
    }

    public function delete(ProductSlugsRequest $request)
    {
        $viewModel = $this->interactor->delete($request->validated()['slugs']);

        if ($viewModel instanceof JsonResourceViewModel) {
            return $viewModel->getResource();
        }
        return null;
    }

    public function status(ProductSlugsRequest $request)
    {
        $requestV = $request->validated();
        $viewModel = $this->interactor->status($requestV['slugs'], $requestV['status']);

        if ($viewModel instanceof JsonResourceViewModel) {
            return $viewModel->getResource();
        }
        return null;
    }
}
