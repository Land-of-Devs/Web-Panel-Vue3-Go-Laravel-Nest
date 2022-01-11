<?php

namespace App\Repositories;

use App\Helpers\FileUploader;
use App\Domain\Interfaces\Products\ProductEntity;
use App\Domain\Interfaces\Products\ProductRepository;
use Illuminate\Support\Str as Str;
use App\Models\Product;
use Illuminate\Http\UploadedFile as file;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use App\Traits\RepositoryUtilsTrait;
use stdClass;

class ProductDbRepository implements ProductRepository
{
    use RepositoryUtilsTrait;

    public function find(string $slug): ?ProductEntity
    {
        return Product::with('user')->where('slug', '=', $slug)->first();
    }

    public function myProducts(): object
    {
        $creator =  auth()->user()->id;
        $where = [
            array(
                'key' => 'status',
                'exp' => '=',
                'value' => request('status')
            ),
            array(
                'key' => 'creator',
                'exp' => '=',
                'value' => $creator
            )
        ];
        $list = Product::orderBy('created_at', 'desc')
            ->with('user')
            ->where(self::cleanWhere($where))
            ->paginate(10);

        return $list;
    }

    public function all(): object
    {
        $where = [
            array(
                'key' => 'status',
                'exp' => '=',
                'value' => request('status')? request('status') : ''
            )
        ];

        return Product::with('user')->orderBy('created_at', 'desc')
            ->where(self::cleanWhere($where))
            ->paginate(10);
    }

    public function create(ProductEntity $product, file $image): ?ProductEntity
    {
        $product->setCreator(auth()->user()->id);
        $product->setSlug(Str::slug($product->getName()) . '-' . time());

        try {
            $product->saveProduct();

            if ($image) {
                $product->setImage(
                    FileUploader::store(
                        $image,
                        Str::slug($product->getName() . '-' . $product->getId()),
                        'img/products'
                    )
                );
            }

            $product->saveProduct();
            return $this->find($product->getSlug());
        } catch (\Exception $e) {
            FileUploader::delete($product->getImage(), 'img/products');
            return null;
        }
    }

    public function update(string $slug, ProductEntity $product, file $image = null): ?ProductEntity
    {
        $toUpdate = $this->find($slug);
        if ($toUpdate->getCreator() == auth()->user()->id) {
            if ($product->getName()) {
                $toUpdate->setSlug(Str::slug($product->getName()) . '-' . time());
                $toUpdate->setName($product->getName());
            }

            if ($image) {
                $toUpdate->setImage(FileUploader::update(
                    $image,
                    $toUpdate->getName() . '-' . $toUpdate->getId(),
                    'img/products',
                    $toUpdate->getImage()
                ) . '?v=' . time());
            }
            $toUpdate->fillProduct(self::cleanArray([
                'description' => $product->description,
                'price' => $product->price
            ]));
            $toUpdate->saveProduct();
            return $this->find($toUpdate->getSlug());
        } else {
            throw new AccessDeniedHttpException();
        }
    }

    public function delete($slugs): object
    {
        try {
            $result = new stdClass();
            $result->count = 0;
            $result->keys = [];
            $result->result = false;
            foreach ($slugs as $slug) {
                $product = $this->find($slug);
                if ($product) {
                    FileUploader::delete($product->image, 'img/products');
                    $product->deleteProduct();
                    $result->count++;
                    array_push($result->keys, $slug);
                }
            }
            $result->result = true;
            return $result;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function status($slugs, $status): object
    {
        try {
            $result = new stdClass();
            $result->count = 0;
            $result->keys = [];
            $result->result = false;
            foreach ($slugs as $slug) {
                $product = $this->find($slug);
                if ($product) {
                    $product->setStatus($status);
                    $product->saveProduct();
                    $result->count++;
                    array_push($result->keys, $slug);
                }
            }
            $result->result = true;
            return $result;
        } catch (\Exception $e) {
            throw $e;
        }
    }
}
