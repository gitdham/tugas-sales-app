<?php

namespace App\Livewire\Pages;

use App\Models\Product;
use App\Repositories\CategoryRepository;
use App\Repositories\ProductRepository;
use Exception;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Title('Products')]
class ProductPage extends Component {
  use WithPagination;

  private ProductRepository $productRepository;
  private CategoryRepository $categoryRepository;

  public ?Product $product;

  public string $name;
  public string $category = '';
  public string $price;
  public string $stock;

  public string $search = '';
  public string $orderBy = 'name';

  public function boot(
    ProductRepository $productRepository,
    CategoryRepository $categoryRepository
  ) {
    $this->productRepository = $productRepository;
    $this->categoryRepository = $categoryRepository;
  }

  #[Computed()]
  public function products() {
    return $this->productRepository->getPaginate(
      search: $this->search,
      orderBy: $this->orderBy,
    );
  }

  #[Computed()]
  public function categories() {
    return $this->categoryRepository->getAll();
  }

  public function rules() {
    return [
      'name' => 'required|unique:products,name',
      'category' => 'required|exists:categories,id',
      'price' => 'required|numeric|min:0',
      'stock' => 'required|numeric|min:0',
    ];
  }

  // #[On('select-product')]
  public function selectProduct(int $productId) {
    $this->product = $this->productRepository->findById($productId);

    $this->name = $this->name = $this->product->name;
    $this->category = $this->category = $this->product->category_id;
    $this->price = $this->price = $this->product->price;
    $this->stock = $this->stock = $this->product->stock;

    $this->dispatch('open-product-form');
  }

  public function createProduct() {
    $this->validate();
    $data = [
      'name' => $this->name,
      'category_id' => $this->category,
      'price' => $this->price,
      'stock' => $this->stock,
    ];

    $this->productRepository->store($data);
    $this->resetForm();
    $this->dispatch('close-product-form');
  }

  public function updateProduct() {
    $data = [
      'name' => $this->name,
      'category_id' => $this->category,
      'price' => $this->price,
      'stock' => $this->stock,
    ];

    $this->productRepository->update($this->product->id, $data);
    $this->resetForm();
    $this->dispatch('close-product-form');
  }

  public function deleteProduct(int $productId) {
    try {
      $this->productRepository->delete($productId);
    } catch (Exception $e) {
      $errorMsg = $e->getMessage();
      $errorConstraintViolation = str_contains($errorMsg, 'Integrity constraint violation: 1451 Cannot delete or update');

      if ($errorConstraintViolation) $this->js("alert('Unable to delete this item because it\’s linked to other records. Please remove any related items first, then try again')");
    }
  }

  public function resetForm() {
    $this->reset([
      'product',
      'name',
      'category',
      'price',
      'stock'
    ]);

    $this->resetValidation([
      'name',
      'category',
      'price',
      'stock'
    ]);
  }

  function setOrder(string $columnName) {
    $validOrderCol = ['name', 'price', 'stock', 'category'];
    if (!in_array($columnName, $validOrderCol)) return $this->orderBy = 'name';
    $this->orderBy = $columnName;
  }

  public function render() {
    return view('livewire.pages.product-page');
  }
}
