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
  public array $saleProducts = [];

  public string $saleProduct = '';
  public int $saleProductQty;

  public ?int $totalQty = 0;
  public ?int $totalAmmount = 0;

  public string $search = '';
  public array $orderBy = ['column' => 'date', 'direction' => 'desc'];

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

  function setOrder(string $columnName) {
    $validOrderCol = ['date', 'total_amount', 'products_count'];
    if (!in_array($columnName, $validOrderCol))
      return $this->orderBy = ['column' => 'date', 'direction' => 'desc'];

    $this->orderBy['direction'] = ($this->orderBy['column'] === $columnName && $this->orderBy['direction'] === 'desc')
      ? 'asc'
      : 'desc';

    $this->orderBy['column'] = $columnName;
  }

  #[Computed()]
  public function sales() {
    return $this->salesRepository->getPaginate(
      orderBy: $this->orderBy,
    );
  }

  #[Computed()]
  public function products() {
    return $this->productRepository->getAll();
  }

  public function addSaleProduct() {
    $rules = [
      'saleProduct' => 'required|exists:products,id',
      'saleProductQty' => 'required|numeric|min:1',
    ];
    $this->validate($rules);

    $selectProduct = $this->productRepository->findById($this->saleProduct);
    $this->saleProducts[] = [
      'product' => $selectProduct,
      'qty' => $this->saleProductQty
    ];

    $this->calculateTotalSaleProducts();
    $this->reset(['saleProduct', 'saleProductQty']);
  }

  public function removeSaleProduct($productIdx) {
    array_splice($this->saleProducts, $productIdx, 1);
    $this->calculateTotalSaleProducts();
  }

  public function calculateTotalSaleProducts() {
    $this->totalQty = array_reduce($this->saleProducts, fn($carry, $item) => $carry + $item['qty']);
    $this->totalAmmount = array_reduce($this->saleProducts, fn($carry, $item) => $carry + $item['product']['price'] * $item['qty']);
  }

  private $saleValidationRule = [
    'date' => 'required|date_format:Y-m-d',
    'totalAmmount' => 'required|numeric',
    'saleProducts' => 'required|array',
    'saleProducts.*.qty' => 'required|numeric|min:1',
  ];

  public function createSale() {
    $this->validate($this->saleValidationRule);

    $data = [
      'sale' => [
        'date' => $this->date,
        'total_amount' => $this->totalAmmount,
      ],
      'products' => array_map(fn($saleProduct) => [
        'id' => $saleProduct['product']['id'],
        'price' => $saleProduct['product']['price'],
        'qty' => $saleProduct['qty']
      ], $this->saleProducts)
    ];

    $this->salesRepository->store($data);
    $this->resetSaleFrom();
    $this->dispatch('close-sale-form');
  }

  public function selectSale(int $saleId) {
    $this->sale = $this->salesRepository->findById($saleId);
    $this->date = $this->sale->date;
    $this->saleProducts = $this->sale->products->map(fn($product) => [
      'product' => $product,
      'qty' => $product->pivot->qty
    ])->toArray();
    $this->calculateTotalSaleProducts();

    $this->dispatch('open-sale-form');
  }

  public function updateSale() {
    $this->validate($this->saleValidationRule);

    $data = [
      'sale' => [
        'date' => $this->date,
        'total_amount' => $this->totalAmmount,
      ],
      'products' => array_map(fn($saleProduct) => [
        'id' => $saleProduct['product']['id'],
        'price' => $saleProduct['product']['price'],
        'qty' => $saleProduct['qty']
      ], $this->saleProducts)
    ];

    $this->salesRepository->update($this->sale->id, $data);
    $this->resetSaleFrom();
    $this->dispatch('close-sale-form');
  }

  public function deleteSale(int $saleId) {
    $this->salesRepository->delete($saleId);
  }

  function resetSaleFrom() {
    $this->reset(['sale', 'date', 'saleProducts', 'totalQty', 'totalAmmount']);
    $this->resetValidation(['date', 'totalAmmount', 'saleProducts', 'saleProducts.*.qty']);
  }

  public function render() {
    return view('livewire.pages.dashboard-page');
  }
}
