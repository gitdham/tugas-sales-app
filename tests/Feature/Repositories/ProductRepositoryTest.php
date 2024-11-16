<?php

use App\Models\Category;
use App\Models\Product;
use App\Repositories\ProductRepository;
use Illuminate\Database\Eloquent\ModelNotFoundException;

beforeEach(function () {
  $this->repository = new ProductRepository();
});

it('can successfully init repository', function () {
  expect($this->repository)->not->toBeNull();
});

it('can get product by id', function () {
  $product = Product::factory()->create();
  $result = $this->repository->findById($product->id);
  expect($product->id)->toBe($result->id);
  expect($product->name)->toBe($result->name);
});

it('throws not found exception when product not found', function () {
  $invalidId = 999;

  expect(fn() => $this->repository->findById($invalidId))
    ->toThrow(ModelNotFoundException::class);
});

it('can get products in paginate', function () {
  Product::factory(13)->create();

  $result = $this->repository->getPaginate();
  expect(count($result))->toBe(10);
});

it('can sucessfully store new product', function () {
  $data = [
    'name' => fake()->unique()->word(),
    'price' => fake()->randomNumber(5, true),
    'stock' => fake()->numberBetween(0, 512),
    'category_id' => Category::factory()->create()->id,
  ];
  $result = $this->repository->store($data);

  $this->assertDatabaseHas('products', [
    'name' => $data['name'],
    'price' => $data['price'],
    'stock' => $data['stock'],
    'category_id' => $data['category_id'],
  ]);
});

it('can sucessfully update selected product', function () {
  $oldProduct = Product::factory()->create();
  $data = ['name' => fake()->word()];
  $result = $this->repository->update($oldProduct->id, $data);

  $this->assertDatabaseMissing('products', $oldProduct->toArray());
  $this->assertDatabaseHas('products', $data);
});

it('can succesfully delete selected product', function () {
  $oldProduct = Product::factory()->create();
  $result = $this->repository->delete($oldProduct->id);

  $this->assertDatabaseMissing('products', $oldProduct->toArray());
});
