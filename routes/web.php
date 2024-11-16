<?php

use App\Livewire\Pages\CategoryPage;
use App\Livewire\Pages\DashboardPage;
use App\Livewire\Pages\ProductPage;
use Illuminate\Support\Facades\Route;

Route::get('/', DashboardPage::class);
Route::get('/categories', CategoryPage::class);
Route::get('/products', ProductPage::class);
