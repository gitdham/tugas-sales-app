<?php

use App\Livewire\Pages\CategoryPage;
use App\Livewire\Pages\ProductPage;
use Illuminate\Support\Facades\Route;

Route::get('/categories', CategoryPage::class);
Route::get('/products', ProductPage::class);
