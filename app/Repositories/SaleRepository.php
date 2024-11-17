<?php

namespace App\Repositories;

use App\Models\Sale;

class SaleRepository {
  public function findById(int $saleId) {
    return Sale::findOrFail($saleId);
  }

  /**
   * Get sales data in pagination and custom order.
   *
   * @param int $perPage per page data to show. default is 10.
   * @param array{
   *  column: string,
   *  direction: string
   * }$orderBy An array scope of order colum.
   */
  public function getPaginate(
    int $perPage = 10,
    array $orderBy = ['column' => 'date', 'direction' => 'desc']
  ) {
    return Sale::query()
      ->select('id', 'date', 'total_amount')
      ->with(['products'])
      ->withCount('products')
      ->orderBy($orderBy['column'], $orderBy['direction'])
      ->paginate($perPage);
  }

  /**
   * Create new sale.
   *
   * @param array{
   *  sale: array{
   *    date: string,
   *    total_amout: decimal
   *  },
   *  products: array{
   *    id: int,
   *    sale_price: decimal
   *    qty: int
   *  }
   * }$data An array of data to store.
   */
  public function store(array $data) {
    $sale = Sale::create($data['sale']);
    $products = collect($data['products'])->mapWithKeys(fn($product) => [
      $product['id'] => [
        'sale_price' => $product['price'],
        'qty' => $product['qty'],
      ]
    ]);

    $sale->products()->attach($products);
    return $sale;
  }

  /**
   * Update slected sale.
   *
   * @param int $saleId Selected sale record to update
   * @param array{
   *  sale: array{
   *    date: string,
   *    total_amout: decimal
   *  },
   *  products: array{
   *    id: int,
   *    sale_price: decimal
   *    qty: int
   *  }
   * }$data An array of data to update.
   */
  public function update(int $saleId, array $data) {
    $sale = $this->findById($saleId);

    $sale->update($data['sale']);

    $products = collect($data['products'])->mapWithKeys(fn($product) => [
      $product['id'] => [
        'sale_price' => $product['price'],
        'qty' => $product['qty'],
      ]
    ]);

    $sale->products()->sync($products);
    return $sale;
  }

  public function delete(int $saleId) {
    $sale = $this->findById($saleId);
    return $sale->delete();
  }
}
