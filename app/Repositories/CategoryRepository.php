<?php

namespace App\Repositories;

use App\Models\Category;

class CategoryRepository {
  public function findById(int $categoryId) {
    return Category::findOrFail($categoryId);
  }

  public function getAll() {
    return  Category::query()
      ->select('id', 'name')
      ->orderBy('name')
      ->get();
  }

  public function getPaginate(int $perPage = 10) {
    return Category::query()
      ->select('id', 'name')
      ->orderBy('name')
      ->paginate($perPage);
  }

  /**
   * Create new category.
   *
   * @param array{
   *  name: string,
   * }$data An array of data to store.
   */
  public function store(array $data) {
    return Category::create($data);
  }

  /**
   * Update selected category.
   *
   * @param int $categoryId Selected category record to update
   * @param array{
   *  name: string,
   * }$data An array of changed data to update.
   */
  public function update(int $categoryId, array $data) {
    $category = $this->findById($categoryId);
    return $category->update($data);
  }

  public function delete(int $categoryId) {
    $category = $this->findById($categoryId);
    return $category->delete();
  }
}
