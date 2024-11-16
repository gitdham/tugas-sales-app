<?php

namespace App\Repositories;

use App\Models\Category;
use App\Models\Product;

class ProductRepository {
  public function findById(int $productId) {
    return Product::findOrFail($productId);
  }

  public function getAll() {
    return  Product::query()
      ->select('id', 'name')
      ->orderBy('name')
      ->get();
  }

  public function getPaginate(int $perPage = 10, string $search = '', string $orderBy = 'name') {
    return Product::query()
      ->select('id', 'name', 'price', 'stock', 'category_id')
      ->with(['category'])
      ->where('name', 'LIKE', "%$search%")
      ->orWhereHas('category', function ($query) use ($search) {
        $query->where('categories.name', 'LIKE', "%$search%");
      })
      ->when(
        $orderBy === 'category',
        fn($query) => $query->orderBy(
          Category::select('name')
            ->whereColumn('categories.id', 'products.category_id')
            ->limit(1)
        ),
        fn($query) => $query->orderBy($orderBy)
      )
      ->paginate($perPage);
  }

  /**
   * Create new product.
   *
   * @param array{
   *  name: string,
   *  price: decimal,
   *  stock: int,
   *  category_id: int,
   * }$data An array of data to store.
   */
  public function store(array $data) {
    return Product::create($data);
  }

  /**
   * Update selected category.
   *
   * @param int $categoryId Selected product record to update
   * @param array{
   *  name: string,
   *  price: decimal,
   *  stock: int,
   *  category_id: int,
   * }$data An array of changed data to update.
   */
  public function update(int $productId, array $data) {
    $product = $this->findById($productId);
    return $product->update($data);
  }

  public function delete(int $productId) {
    $product = $this->findById($productId);
    return $product->delete();
  }
}
