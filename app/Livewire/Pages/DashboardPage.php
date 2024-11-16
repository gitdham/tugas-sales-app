<?php

namespace App\Livewire\Pages;

use App\Models\Sale;
use App\Repositories\ProductRepository;
use App\Repositories\SaleRepository;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Title('Sales Dashboard')]
class DashboardPage extends Component {
  use WithPagination;

  private SaleRepository $salesRepository;
  private ProductRepository $productRepository;

  public ?Sale $sale;

  public string $date;
  public array $saleProducts;

  public string $search = '';
  // public string $orderBy = 'name';

  public function boot(
    SaleRepository $salesRepository,
    ProductRepository $productRepository,
  ) {
    $this->salesRepository = $salesRepository;
    $this->productRepository = $productRepository;
  }

  public function mount() {
    $this->date = date('Y-m-d');
  }

  #[Computed()]
  public function sales() {
    return $this->salesRepository->getPaginate(
      // search: $this->search,
      // orderBy: $this->orderBy,
    );
  }

  #[Computed()]
  public function products() {
    return $this->productRepository->getAll();
  }

  public function render() {
    return view('livewire.pages.dashboard-page');
  }
}
