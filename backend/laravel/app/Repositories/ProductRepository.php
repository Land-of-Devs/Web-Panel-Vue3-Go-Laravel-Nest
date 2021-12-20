<?php

namespace App\Repositories;

use App\Helpers\FileUploader;
use App\Interfaces\ApiProductInterface;
use Illuminate\Support\Str as Str;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;

class ProductRepository implements ApiProductInterface
{
        
    public function myProducts()
    {
        $creator =  '315dd65e-f69e-4f84-a929-2a9517f02df7';
        return Product::orderBy('created_at', 'desc')
        ->with('user')
        ->where('creator', $creator)
        ->paginate(9);
    }
    
    public function all()
    {
        return Product::with('user')->orderBy('created_at', 'desc')
        ->paginate(9);
    }
    
    public function create(array $data)
    {
        $data['creator'] = '4cf0a33d-e59c-48c6-9c13-232d483e2f20';
        //auth()->guard()->user()->id; 
        $data['slug'] = Str::slug($data['name']) . '-' . time();
        $product = Product::with('user')->create($data);
        if (isset($data['image'])) {
            if ($data['image'] != null && $data['image'] != '' && !is_string($data['image'])) {
                $product->image = FileUploader::store($data['image'], Str::slug($data['name'] . '-' . $product->id), 'img/products');
            }
        }
        try {
            $product->save();
            return $this->find($product->slug);
        } catch (\Exception $e) {
            FileUploader::delete($product->image, 'img/products');
            throw $e;
        }
    }
    
    public function update($id, array $data)
    {
        if ($data) {
            $product = $this->find($id);
            if (isset($data['name'])) {
                if ($data['name'] != null && $data['name'] != '' && is_string($data['name'])) {
                    $product->slug = Str::slug($data['name']) . '-' . time();
                    $product->name = $data['name'];
                }
            }
            if (isset($data['image'])) {
                if ($data['image'] != null && $data['image'] != '' && is_file($data['image'])) {
                    $product->image = FileUploader::update($data['image'], $product->name . '-' . $product->id, 'img/products', $product->image);
                }
            }
            $product->fill($data);
            $product->save();
            return $this->find($product->slug);
        }
    }

    public function delete($slugs)
    {
        try {
            foreach ($slugs as $slug) {
                $product = $this->find($slug);
                if ($product) {
                    FileUploader::delete($product->image, 'img/products');
                    $product->delete();
                }
            }
            return true;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function find($id)
    {
        return Product::with('user')->where('slug', '=', $id)->first();
    }
    
    public function paginate($perPage)
    {
        $perPage = isset($perPage) ? $perPage : 9;
        return Product::orderBy('created_at', 'desc')
            ->with('user')
            ->paginate($perPage);
    }

    // public function search($keyword, $perPage, $user = null)
    // {
    //     $perPage = isset($perPage) ? $perPage : 9;
    //     return Product::when(
    //         $user != null,
    //         function ($q) use ($user) {
    //             return $q->where('creator', $user);
    //         }
    //     )
    //         ->where('title', 'like', '%' . $keyword . '%')
    //         ->orWhere('description', 'like', '%' . $keyword . '%')
    //         ->orWhere('price', 'like', '%' . $keyword . '%')
    //         ->orderBy('created_at', 'desc')
    //         ->with('user')
    //         ->paginate($perPage);
    // }
}
