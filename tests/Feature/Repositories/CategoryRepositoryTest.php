<?php

use App\Models\Category;
use App\Repositories\CategoryRepository;
use Illuminate\Database\Eloquent\ModelNotFoundException;

beforeEach(function () {
  $this->repository = new CategoryRepository();
});

it('can successfully init repository', function () {
  expect($this->repository)->not->toBeNull();
});

it('can get category by id', function () {
  $category = Category::factory()->create();
  $result = $this->repository->findById($category->id);
  expect($category->id)->toBe($result->id);
  expect($category->name)->toBe($result->name);
});

it('throws not found exception when category not found', function () {
  $invalidId = 999;

  expect(fn() => $this->repository->findById($invalidId))
    ->toThrow(ModelNotFoundException::class);
});

it('can get categories in paginate', function () {
  Category::factory(13)->create();

  $result = $this->repository->getPaginate();
  expect(count($result))->toBe(10);
});

it('can sucessfully store new category', function () {
  $data = ['name' => fake()->word()];
  $result = $this->repository->store($data);

  $this->assertDatabaseHas('categories', [
    'name' => $data['name'],
  ]);
});

it('can sucessfully update selected category', function () {
  $oldCategory = Category::factory()->create();
  $data = ['name' => fake()->word()];
  $result = $this->repository->update($oldCategory->id, $data);

  $this->assertDatabaseMissing('categories', $oldCategory->toArray());
  $this->assertDatabaseHas('categories', $data);
});

it('can succesfully delete selected category', function () {
  $oldCategory = Category::factory()->create();
  $result = $this->repository->delete($oldCategory->id);

  $this->assertDatabaseMissing('categories', $oldCategory->toArray());
});
