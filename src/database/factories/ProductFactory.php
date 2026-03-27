<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition()
    {
        return [
            'user_id' => User::factory(),
            'name' => $this->faker->word(),
            'brand' => $this->faker->optional()->company(),
            'description' => $this->faker->sentence(),
            'price' => $this->faker->numberBetween(300, 50000),
            'condition' => $this->faker->randomElement([
                '良好',
                '目立った傷や汚れなし',
                'やや傷や汚れあり',
                '状態が悪い',
            ]),
            'image_path' => 'product_images/dummy.jpg',
            'is_sold' => false,
        ];
    }

    public function sold()
    {
        return $this->state(fn () => [
            'is_sold' => true,
        ]);
    }
}
