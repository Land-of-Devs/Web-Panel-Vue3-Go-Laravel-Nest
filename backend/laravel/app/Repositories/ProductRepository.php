<?php
namespace App\Repositories;

use App\Helpers\FileUploader;
use App\Interfaces\ApiCrudInterface;
use App\Http\Resources\ProductCollection;
use Illuminate\Support\Str as Str;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;

class ProductRepository implements ApiCrudInterface{
	
    public function all()
    {
        return Product::orderBy('create_at', 'desc')
	        ->with('user')
	        ->paginate(9);
    }

    public function myProducts()
    {
        return Product::orderBy('create_at', 'desc')
            ->with('user')
            ->where('user_id', auth()->guard()->user()->id)
            ->paginate(9);
    }

    public function paginate($perPage)
    {
        $perPage = isset($perPage) ? $perPage : 9;
        return Product::orderBy('id', 'desc')
	        ->with('user')
	        ->paginate($perPage);
    }

    public function search($keyword, $perPage, $user = null)
    {
        $perPage = isset($perPage) ? $perPage : 9;
        return Product::when($user != null, 
            function ($q) use ($user) {
                return $q->where('user_id', $user);
            })
            ->where('title', 'like', '%'.$keyword.'%')
	        ->orWhere('description', 'like', '%'.$keyword.'%')
	        ->orWhere('price', 'like', '%'.$keyword.'%')
	        ->orderBy('id', 'desc')
	        ->with('user')
	        ->paginate($perPage);
    }
    
    public function create(array $data)
    {
        // $data['creator'] = auth()->guard()->user()->id; 

        $data['slug'] = Str::slug($data['name']) . '-' . time();
        // upload image file
        if(isset($data['image'])){ 
            if($data['image'] != null && $data['image'] != '' && !is_string($data['image'])){
                $data['image']   = FileUploader::store('image', $data['image'], $data['slug'], 'products' ,'/app_data/img/products');  
            } 
        }     
        return Product::create($data)->with('user');
    }

    public function find($id){
        return Product::where('slug', '=', $id)
            ->with('user');
    }

    public function update($id, array $data)
    {
        $product = Product::with('user')->where('slug', '=', $id);
        if($product){
            if(isset($data['image'])){ 
                if($data['image'] != null && $data['image'] != '' && !is_string($data['image'])){
                    $data['image']   = FileUploader::update('image', $data['image'], $data['title'] , 'products','/app_data/img/products', $product->image);            
                } 
            }
            # update product
            $product->update($data);
            return $this->find($product->slug);
        }
    }

    public function delete($id)
    {
        $product = Product::find($id);
        if($product){
            // FileUploader::delete('gallery/products/'.$product->image);
            $product->delete($product);
            return true;
        }
        return false;
    }
}