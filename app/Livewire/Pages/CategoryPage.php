<?php

namespace App\Livewire\Pages;

use App\Models\Category;
use App\Repositories\CategoryRepository;
use Exception;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Title('Product Categories')]
class CategoryPage extends Component {
  use WithPagination;

  private CategoryRepository $categoryRepository;

  public string $name;
  public ?Category $category;

  public function boot(CategoryRepository $categoryRepository) {
    $this->categoryRepository = $categoryRepository;
  }

  public function rules() {
    return [
      'name' => 'required|unique:categories,name',
    ];
  }

  #[Computed()]
  public function categories() {
    return $this->categoryRepository->getPaginate();
  }

  public function clearForm() {
    $this->reset(['category', 'name']);
  }

  public function selectCategory(int $categoryId) {
    $this->category = $this->categoryRepository->findById($categoryId);
    $this->name = $this->category->name;
  }

  public function createCategory() {
    $this->validate();

    $this->categoryRepository->store(['name' => $this->name]);
    $this->clearForm();
  }

  public function updateCategory() {
    $this->validate();

    $this->categoryRepository->update(
      $this->category->id,
      ['name' => $this->name]
    );

    $this->clearForm();
  }

  public function deleteCategory(int $categoryId) {
    try {
      $this->categoryRepository->delete($categoryId);
    } catch (Exception $e) {
      $errorMsg = $e->getMessage();
      $errorConstraintViolation = str_contains($errorMsg, 'Integrity constraint violation: 1451 Cannot delete or update');

      if ($errorConstraintViolation) $this->js("alert('Unable to delete this item because it\’s linked to other records. Please remove any related items first, then try again')");
    }
  }

  public function render() {
    return view('livewire.pages.category-page');
  }
}
