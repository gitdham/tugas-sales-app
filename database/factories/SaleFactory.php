<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\Sale;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Sales>
 */
class SaleFactory extends Factory {
  public function configure() {
    return $this->afterCreating(function (Sale $sale) {
      $products = Product::inRandomOrder()->take(rand(1, 5))->pluck('id');
      foreach ($products as $productId) {
        $sale->products()->attach($productId, [
          'sale_price' => rand(1000, 100000),
          'qty' => rand(1, 10),
        ]);
      }
    });
  }

  /**
   * Define the model's default state.
   *
   * @return array<string, mixed>
   */
  public function definition(): array {
    return [
      'date' => fake()->dateTimeThisYear()->format('Y_m_d'),
      'total_amount' => fake()->randomNumber(5, true),
    ];
  }
}
