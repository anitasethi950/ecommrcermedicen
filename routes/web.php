<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CareerController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\Usercontroller;

Route::get('/', function () {
    return view('login');
});
Route::post('/login', [Usercontroller::class, 'login'])->name('login.post');
Route::get('/dashboard', [Usercontroller::class, 'dashboard'])->name('admin.dashboard');

Route::get('/category', [CategoryController::class, 'category'])->name('admin.category');
Route::post('/category', [CategoryController::class, 'categorystore'])->name('category.store');
Route::get('/categoryview', [CategoryController::class, 'viewcategory'])->name('category.view');
Route::get('/category/{id}/edit', [CategoryController::class, 'categoryedit'])->name('category.edit');
Route::put('category/{id}', [CategoryController::class, 'update'])->name('category.update');
Route::delete('category/{id}', [CategoryController::class, 'destroy'])->name('category.destroy');

Route::get('/product', [ProductController::class, 'product'])->name('admin.product');
Route::post('/product', [ProductController::class, 'productstore'])->name('product.store');
Route::get('/productview', [ProductController::class, 'productview'])->name('product.view');
Route::get('products/{id}/edit', [ProductController::class, 'productedit'])->name('products.edit');
Route::put('products/{id}', [ProductController::class, 'update'])->name('products.update');
Route::delete('products/{id}', [ProductController::class, 'destroy'])->name('products.destroy');
Route::delete('product_images/{id}', [ProductController::class, 'productimagedestroy'])->name('product_images.destroy');
Route::get('productviewimage/{id}', [ProductController::class, 'productviewimage'])->name('productimage.view');
Route::delete('products/{id}', [ProductController::class, 'destroy'])->name('products.destroy');
