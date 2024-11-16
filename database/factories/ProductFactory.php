<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory {
  /**
   * Define the model's default state.
   *
   * @return array<string, mixed>
   */
  public function definition(): array {
    return [
      'name' => fake()->unique()->word(),
      'price' => fake()->randomNumber(5, true),
      'stock' => fake()->numberBetween(0, 512),
      'category_id' => Category::factory(),
    ];
  }
}
