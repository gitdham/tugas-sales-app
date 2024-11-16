<?php

use App\Models\Product;
use App\Models\Sale;
use App\Repositories\SaleRepository;

beforeEach(function () {
  $this->repository = new SaleRepository();
});

it('can successfully init repository', function () {
  expect($this->repository)->not->toBeNull();
});

it('can get sale by id', function () {
  $sale = Sale::factory()->create();
  $result = $this->repository->findById($sale->id);
  expect($sale->id)->toBe($result->id);
  expect($sale->name)->toBe($result->name);
});

it('can get sales in paginate', function () {
  Sale::factory(13)->create();

  $result = $this->repository->getPaginate();
  expect(count($result))->toBe(10);
});

it('can store sale with products', function () {
  $products = Product::factory(3)->create();

  $data = [
    'sale' => [
      'date' => date('Y-m-d'),
      'total_amount' => fake()->randomNumber(5, true),
    ],
    'products' => $products->map(fn($product) => [
      'id' => $product->id,
      'price' => $product->price,
      'qty' => rand(1, 10),
    ])
  ];

  $result = $this->repository->store($data);

  $this->assertDatabaseHas('sales', [
    'date' => $data['sale']['date'],
    'total_amount' => $data['sale']['total_amount'],
  ]);

  $this->assertDatabaseHas('sale_products', [
    'sale_id' => $result->id,
    'product_id' => $data['products'][0]['id'],
    'sale_price' => $data['products'][0]['price'],
    'qty' => $data['products'][0]['qty'],
  ]);
});

it('can update selected sale with products', function () {
  $products = Product::factory(3)->create();
  $sale  = Sale::factory()->create();

  $data = [
    'sale' => [
      'date' => date('Y-m-d'),
      'total_amount' => fake()->randomNumber(5, true),
    ],
    'products' => $products->map(fn($product) => [
      'id' => $product->id,
      'price' => $product->price,
      'qty' => rand(1, 10),
    ])
  ];

  $result = $this->repository->update($sale->id, $data);

  $this->assertDatabaseMissing('sales', $sale->toArray());

  $this->assertDatabaseHas('sales', [
    'date' => $data['sale']['date'],
    'total_amount' => $data['sale']['total_amount'],
  ]);

  $this->assertDatabaseHas('sale_products', [
    'sale_id' => $result->id,
    'product_id' => $data['products'][0]['id'],
    'sale_price' => $data['products'][0]['price'],
    'qty' => $data['products'][0]['qty'],
  ]);
});

it('can delete selected sale', function () {
  $sale  = Sale::factory()->create();

  $result = $this->repository->delete($sale->id);

  $this->assertDatabaseMissing('sales', $sale->toArray());
  $this->assertDatabaseMissing('sale_products', $sale->products->toArray());
});
