<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Haruncpi\LaravelIdGenerator\IdGenerator;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        // $config = ['table'=>'products', 'length'=>11, 'field'=>'product_id', 'prefix'=>'PRDCT-'];
        // $idProduct = IdGenerator::generate($config);

        return [
            'product_id' => 'PRDCT-00001',
            'product_name' => fake()->name(),
            'price' => 1000,
            'quantity' => 10,
            'total_amount' => 10000,
        ];
    }
}
