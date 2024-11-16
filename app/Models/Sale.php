<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sale extends Model {
  /** @use HasFactory<\Database\Factories\SalesFactory> */
  use HasFactory;

  /**
   * The attributes that are mass assignable.
   *
   * @var array<int, string>
   */
  protected $fillable = [
    'date',
    'total_amount',
  ];

  /**
   * Get the products in sale.
   */
  public function products() {
    return $this->belongsToMany(Product::class, 'sale_products')
      ->withPivot('sale_price', 'qty');
  }
}
