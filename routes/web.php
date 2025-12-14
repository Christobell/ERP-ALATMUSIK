<?php

use App\Http\Controllers\BillOfMaterialController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ManufacturingOrderController;
use App\Http\Controllers\MaterialController;
use App\Http\Controllers\ProductController;
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
