<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\Sale;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SaleSeeder extends Seeder {
  /**
   * Run the database seeds.
   */
  public function run(): void {
    // Create categories
    $categories = Category::factory(4)->create();

    $categoryIds = $categories->pluck('id')->all();

    // Create products
    Product::factory()
      ->count(34)
      ->sequence(fn() => ['category_id' => $categoryIds[array_rand($categoryIds)]])
      ->create();

    Sale::factory(34)->create();
  }
}
