<?php

use App\Http\Controllers\BillOfMaterialController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ManufacturingOrderController;
use App\Http\Controllers\MaterialController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\VendorController;
use App\Http\Controllers\PurchaseOrderController;
use Illuminate\Support\Facades\Route;

Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

Route::get('/manufaturing/material', [MaterialController::class, 'index'])->name('material.index');
Route::get('/manufaturing/material/{id}', [MaterialController::class, 'show'])->name('material.show');
Route::post('/manufaturing/material', [MaterialController::class, 'store'])->name('material.store');
Route::put('/manufaturing/material/{id}', [MaterialController::class, 'update'])->name('material.update');
Route::delete('/manufaturing/material/{id}', [MaterialController::class, 'destroy'])->name('material.destroy');

Route::get('/manufaturing/product', [ProductController::class, 'index'])->name('product.index');
Route::get('/manufaturing/product/{id}', [ProductController::class, 'show'])->name('product.show');
Route::post('/manufaturing/product', [ProductController::class, 'store'])->name('product.store');
Route::put('/manufaturing/product/{id}', [ProductController::class, 'update'])->name('product.update');
Route::delete('/manufaturing/product/{id}', [ProductController::class, 'destroy'])->name('product.destroy');

Route::get('/manufaturing/bom', [BillOfMaterialController::class, 'index'])->name('bom.index');
Route::get('/manufaturing/bom/{id}', [BillOfMaterialController::class, 'show'])->name('bom.show');
Route::post('/manufaturing/bom', [BillOfMaterialController::class, 'store'])->name('bom.store');
Route::put('/manufaturing/bom/{id}', [BillOfMaterialController::class, 'update'])->name('bom.update');
Route::delete('/manufaturing/bom/{id}', [BillOfMaterialController::class, 'destroy'])->name('bom.destroy');

Route::get('/manufaturing/order', [ManufacturingOrderController::class, 'index'])->name('mo.index');
Route::get('/manufaturing/order/{id}', [ManufacturingOrderController::class, 'show'])->name('mo.show');
Route::post('/manufaturing/order', [ManufacturingOrderController::class, 'store'])->name('mo.store');
Route::put('/manufaturing/order/{id}', [ManufacturingOrderController::class, 'update'])->name('mo.update');
Route::delete('/manufaturing/order/{id}', [ManufacturingOrderController::class, 'destroy'])->name('mo.destroy');
Route::patch('/manufacturing-order/{id}/status', [ManufacturingOrderController::class, 'updateStatus'])->name('mo.updateStatus');

// Vendor Routes
Route::resource('vendor', VendorController::class);
Route::put('/vendor/{id}/toggle-status', [VendorController::class, 'toggleStatus'])->name('vendor.toggle-status');
Route::get('/vendor/{id}/add-material', [VendorController::class, 'createMaterial'])->name('vendor.create-material');
Route::post('/vendor/{id}/store-material', [VendorController::class, 'storeMaterial'])->name('vendor.store-material');
Route::post('/vendor/add-material', [VendorController::class, 'addMaterial'])->name('vendor.add-material');
Route::delete('/vendor/{vendorId}/remove-material/{itemId}', [VendorController::class, 'removeMaterial'])->name('vendor.remove-material');
Route::put('/vendor/update-material/{itemId}', [VendorController::class, 'updateMaterialPrice'])->name('vendor.update-material-price');
Route::get('/vendor/search', [VendorController::class, 'search'])->name('vendor.search');
Route::get('/vendor/select2', [VendorController::class, 'getVendorsSelect2'])->name('vendor.select2');
Route::get('/vendor/export/{type?}', [VendorController::class, 'export'])->name('vendor.export');

// Purchase Order Routes
Route::resource('purchase-order', PurchaseOrderController::class);
Route::put('/purchase-order/{id}/update-status', [PurchaseOrderController::class, 'updateStatus'])->name('purchase-orders.update-status');
Route::get('/purchase-order/{id}/print', [PurchaseOrderController::class, 'print'])->name('purchase-orders.print');
Route::get('purchase-orders/create', [PurchaseOrderController::class, 'create'])->name('purchase-orders.create');
//Route::middleware(['auth'])->group(function () {
Route::resource('purchase-orders', PurchaseOrderController::class)->except(['show']);
Route::get('purchase-orders/{purchaseOrder}', [PurchaseOrderController::class, 'show'])->name('purchase-orders.show');
Route::post('purchase-orders/{purchaseOrder}/submit', [PurchaseOrderController::class, 'submit'])->name('purchase-orders.submit');
Route::post('purchase-orders/{purchaseOrder}/approve', [PurchaseOrderController::class, 'approve'])->name('purchase-orders.approve');
Route::post('purchase-orders/{purchaseOrder}/reject', [PurchaseOrderController::class, 'reject'])->name('purchase-orders.reject');
Route::post('purchase-orders/{purchaseOrder}/complete', [PurchaseOrderController::class, 'complete'])->name('purchase-orders.complete');
Route::post('purchase-orders/{purchaseOrder}/cancel', [PurchaseOrderController::class, 'cancel'])->name('purchase-orders.cancel');
Route::get('purchase-orders/{purchaseOrder}/print', [PurchaseOrderController::class, 'print'])->name('purchase-orders.print');
Route::post('/purchase-orders', [PurchaseOrderController::class, 'store'])->name('purchase-orders.store');
