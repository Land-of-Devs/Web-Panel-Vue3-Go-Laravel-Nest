<?php

namespace App\Http\Controllers\Api\Products;

use App\Http\Controllers\Controller;
use App\Http\Requests\Products\ProductCreateRequest;
use App\Http\Requests\Products\ProductUpdateRequest;
use Illuminate\Http\Request;
use App\Repositories\ProductRepository;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Response;

class ProductController extends Controller
{
    use ApiResponseTrait;

    public $productRepository;

    public $not_found;

    public function __construct(ProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;
        $this->not_found = Response::HTTP_NOT_FOUND;
    }

    public function index()
    {
        try {
            $data = $this->productRepository->myProducts();
            return self::apiResponseSuccess($data,'Fetched all products!');
            
        } catch (\Exception $e) {
            return self::apiServerError($e->getMessage());
        }
    }

    public function all()
    {
        $data = $this->productRepository->all();
        return self::apiResponseSuccess($data, 'Found '.count($data).' Products');
    }

    public function store(ProductCreateRequest $request)
    {
        try {
            $requestVal = $request->validated();
            $data = $this->productRepository->create($requestVal);
            return self::apiResponseSuccess($data, 'New Product Added!');
        } catch (\Exception $e) {
            return self::apiServerError($e->getMessage());
        }
    }

    public function show($id)
    {
        try {
            $data = $this->productRepository->find($id);
            if(is_null($data)){
                $msg = 'Product Not Found';
                return self::apiResponseError(null, $msg , $this->not_found);
            }
            return self::apiResponseSuccess($data, 'See product details!');
        } catch (\Exception $e) {
            return self::apiServerError($e->getMessage());
        }
    }

    public function update($id, ProductUpdateRequest $request)
    {
        echo json_encode($request->all());
        try {
            $requestVal = $request->validated();
            $data = $this->productRepository->update($id, $requestVal);
            if(is_null($data)){
                $msg = 'Product Not Updated';
                return self::apiResponseError(null, $msg , $this->not_found);
            }
            $msg = 'Product Updated Successfully !'; 
            return self::apiResponseSuccess($data, $msg);
        } catch (\Exception $e) {
            return self::apiServerError($e->getMessage());
        }
    }

    public function delete(Request $request)
    {
        try {
            $data = $this->productRepository->delete($request->slugs);
            if($data){
                $msg = 'Product Deleted Successfully !';
                return self::apiResponseSuccess($data, $msg);
            }
            $msg = 'Product Not Found';
            return self::apiResponseError(null, $msg , $this->not_found);

        } catch (\Exception $e) {

            return self::apiServerError($e->getMessage());
        }
    }

    // public function search(Request $request)
    // {
    //     $data = $this->productRepository->search($request->keyword,$request->page, null);
    //     return self::apiResponseSuccess($data, 'Found '.count($data).' Products');
    // }

    // public function searchMyStore(Request $request)
    // {
    //     $data = $this->productRepository->search($request->keyword,$request->page, auth()->guard()->user()->id);
    //     return self::apiResponseSuccess($data, 'Found '.count($data).' Products');
    // }
}
