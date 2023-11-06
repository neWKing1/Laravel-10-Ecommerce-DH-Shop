<?php

use App\Http\Controllers\Backend\BannerController;
use App\Http\Controllers\Backend\BrandController;
use App\Http\Controllers\Backend\CategoryController;
use App\Http\Controllers\Backend\ChildCategoryController;
use App\Http\Controllers\Backend\CouponController;
use App\Http\Controllers\Backend\OrderController;
use App\Http\Controllers\Backend\OwnerController;
use App\Http\Controllers\Backend\ProductController;
use App\Http\Controllers\Backend\ProductImageGalleryController;
use App\Http\Controllers\Backend\ProductVariantController;
use App\Http\Controllers\Backend\ProductVariantItemController;
use App\Http\Controllers\Backend\SubCategoryController;
use Illuminate\Support\Facades\Route;

/** Owner Route */
Route::get('/dashboard', [OwnerController::class, 'dashboard'])->name('dashboard');

/** Category Route */
Route::put('category/change-status', [CategoryController::class, 'changeStatus'])->name('category.change-status');
Route::resource('category', CategoryController::class);

/** SubCategory Route */
Route::put('sub-category/change-status', [SubCategoryController::class, 'changeStatus'])->name('sub-category.change-status');
Route::resource('sub-category', SubCategoryController::class);

/** ChildCategory Route */
Route::put('child-category/change-status', [ChildCategoryController::class, 'changeStatus'])->name('child-category.change-status');
Route::get('child-category/get-sub-category', [ChildCategoryController::class, 'getSubCategories'])->name('child-category.get-sub-category');
Route::resource('child-category', ChildCategoryController::class);

/** Brand Route */
Route::put('brand/change-status', [BrandController::class, 'changeStatus'])->name('brand.change-status');
Route::resource('brand', BrandController::class);

/** Product Route */
Route::put('product/change-status', [ProductController::class, 'changeStatus'])->name('product.change-status');
Route::get('product/get-sub-category', [ProductController::class, 'getSubCategories'])->name('product.get-sub-category');
Route::get('product/get-child-category', [ProductController::class, 'getChildCategories'])->name('product.get-child-category');
Route::resource('product', ProductController::class);

// /** Product Image Route */
Route::resource('product-image-gallery', ProductImageGalleryController::class);

// /** Product Variant Route */
Route::put('product-variant/change-status', [ProductVariantController::class, 'changeStatus'])->name('product-variant.change-status');
Route::resource('product-variant', ProductVariantController::class);

// /** Product Variant Item Route */
Route::put('product-variant-item/change-status', [ProductVariantItemController::class, 'changeStatus'])->name('product-variant-item.change-status');
Route::resource('product-variant-item', ProductVariantItemController::class);
Route::get('product-variant-item/{variantItemId}/edit', [ProductVariantItemController::class, 'edit'])->name('product-variant-item.edit');
Route::put('product-variant-item/{variantItemId}/update', [ProductVariantItemController::class, 'update'])->name('product-variant-item.update');
Route::delete('product-variant-item/{variantId}', [ProductVariantItemController::class, 'destroy'])->name('product-variant-item.destroy');

Route::get('product-variant-item/{productId}/{variantId}', [ProductVariantItemController::class, 'index'])->name('product-variant-item.index');
Route::get('product-variant-item/create/{productId}/{variantId}', [ProductVariantItemController::class, 'create'])->name('product-variant-item.create');
Route::post('product-variant-item', [ProductVariantItemController::class, 'store'])->name('product-variant-item.store');

/** Order Routes*/
Route::resource('order', OrderController::class);

// Banner Routes
Route::put('banner/change-status', [BannerController::class, 'changeStatus'])->name('banner.change-status');
Route::resource('banner', BannerController::class);

// Coupon Routes
Route::put('coupons/change-status', [CouponController::class, 'changeStatus'])->name('coupons.change-status');
Route::resource('coupons', CouponController::class);