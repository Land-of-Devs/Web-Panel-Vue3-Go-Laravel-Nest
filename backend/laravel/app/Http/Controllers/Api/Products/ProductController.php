<?php

namespace App\Http\Controllers\Api\Products;

use App\Http\Controllers\Controller;
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

    /**
     * All Products by User
     *
     * @return \Illuminate\Http\Response user products 
     */
    public function index()
    {
        try {
            $data = $this->productRepository->myProducts();
            return self::apiResponseSuccess($data,'Fetched all products!');

        } catch (\Exception $e) {
            return self::apiServerError($e->getMessage());
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $data = $this->productRepository->create($request->all());
            return self::apiResponseSuccess($data, 'New Product Added!');
        } catch (\Exception$e) {
            return self::apiServerError($e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
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


    public function update(Request $request, $id)
    {
        try {
            $data = $this->productRepository->update($id, $request->all());
            if(is_null($data)){
                $msg = 'Product Not Found';
                return self::apiResponseError(null, $msg , $this->not_found);
            }
            $msg = 'Product Updated Successfully !'; 
            return self::apiResponseSuccess($data, $msg);
        } catch (\Exception $e) {
            return self::apiServerError($e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $data = $this->productRepository->delete($id);
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

    public function all()
    {
        $data = $this->productRepository->all();
        return self::apiResponseSuccess($data, 'Found '.count($data).' Products');
    }

    public function search(Request $request)
    {
        $data = $this->productRepository->search($request->keyword,$request->page, null);
        return self::apiResponseSuccess($data, 'Found '.count($data).' Products');
    }

    public function searchMyStore(Request $request)
    {
        $data = $this->productRepository->search($request->keyword,$request->page, auth()->guard()->user()->id);
        return self::apiResponseSuccess($data, 'Found '.count($data).' Products');
    }
}
