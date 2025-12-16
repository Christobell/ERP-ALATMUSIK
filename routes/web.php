<?php

use App\Http\Controllers\BillOfMaterialController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ManufacturingOrderController;
use App\Http\Controllers\MaterialController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\VendorController;
use App\Http\Controllers\PurchaseOrderController;
use App\Http\Controllers\RfqController;
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

Route::middleware(['web'])->group(function () {
    Route::prefix('purchase-order')->name('purchase-order.')->group(function () {
        Route::get('/', [PurchaseOrderController::class, 'index'])->name('index');
        Route::get('/create', [PurchaseOrderController::class, 'create'])->name('create');
        Route::post('/', [PurchaseOrderController::class, 'store'])->name('store');
        Route::get('/{id}', [PurchaseOrderController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [PurchaseOrderController::class, 'edit'])->name('edit');
        Route::put('/{id}', [PurchaseOrderController::class, 'update'])->name('update');
        Route::delete('/{id}', [PurchaseOrderController::class, 'destroy'])->name('destroy');
        Route::post('/{id}/approve', [PurchaseOrderController::class, 'approve'])->name('approve');
        Route::get('/{id}/print', [PurchaseOrderController::class, 'print'])->name('print');
    });
});

// RFQ Routes
Route::prefix('rfq')->name('rfq.')->group(function () {
    Route::get('/', [RfqController::class, 'index'])->name('index');
    Route::get('/create', [RfqController::class, 'create'])->name('create');
    Route::post('/', [RfqController::class, 'store'])->name('store');
    Route::get('/{id}', [RfqController::class, 'show'])->name('show');
    Route::get('/{id}/edit', [RfqController::class, 'edit'])->name('edit');
    Route::put('/{id}', [RfqController::class, 'update'])->name('update');
    Route::delete('/{id}', [RfqController::class, 'destroy'])->name('destroy');
    Route::post('/{id}/send', [RfqController::class, 'sendToVendor'])->name('send');
    Route::post('/{id}/approve', [RfqController::class, 'approve'])->name('approve');
    Route::get('/{id}/print', [RfqController::class, 'print'])->name('print');
});