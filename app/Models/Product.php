<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Product extends Model {
  /** @use HasFactory<\Database\Factories\ProductFactory> */
  use HasFactory;

  /**
   * The attributes that are mass assignable.
   *
   * @var array<int, string>
   */
  protected $fillable = [
    'name',
    'category_id',
    'price',
    'stock',
  ];

  /**
   * Get the category that owns product.
   */
  public function category(): BelongsTo {
    return $this->belongsTo(Category::class);
  }

  /**
   * Get the sale that on  in sale.
   */
  public function sales() {
    return $this->belongsToMany(Sale::class, 'sale_products')
      ->withPivot('sale_price', 'qty');
  }
}
