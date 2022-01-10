<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Product;


class ProductFactory extends Factory
{

    protected $model = Product::class;
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'slug' => $this->faker->name(),
            'name' => Str::random(4),
            'description' => 'description product',
            'price' => random_int(10, 10000),
            'creator' => null,
            'image' => 'dummy' . strval(random_int(1, 3)) . '.jpg',
            'status' => 'Complete'
        ];
    }

}

