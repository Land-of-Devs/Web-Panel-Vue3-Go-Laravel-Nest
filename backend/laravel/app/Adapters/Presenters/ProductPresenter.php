<?php

namespace App\Adapters\Presenters;

use App\Adapters\ViewModels\JsonResourceViewModel;
use App\Domain\Interfaces\Products\ProductEntity;
use App\Domain\Interfaces\ViewModel;
use App\Domain\UseCases\Products\ProductOutputPort;
use App\Http\Resources\ErrorResource;
use App\Http\Resources\SuccessResource;
use App\Http\Resources\Products\ProductResource;
use App\Http\Resources\Products\ListResource;


class ProductPresenter implements ProductOutputPort
{
    public function listProducts(object $products): ViewModel
    {
        return new JsonResourceViewModel(
            new ListResource($products)
        );
    }

    public function product(ProductEntity $product): ViewModel
    {
        return new JsonResourceViewModel(
            new ProductResource($product)
        );
    }

    public function success(string $msg, int $code, object $result): ViewModel
    {
        return new JsonResourceViewModel(
            new SuccessResource($msg, $code, $result)
        );
    }

    public function fail(string $msg, int $code ): ViewModel
    {
        return new JsonResourceViewModel(
            new ErrorResource($msg, $code)
        );
    }
}
