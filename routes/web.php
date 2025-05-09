<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\JenisController;
use App\Http\Controllers\KambingController;
use App\Http\Controllers\BarnController;
use App\Http\Controllers\HealthRecordController;
use App\Http\Controllers\FeedController;
use App\Http\Controllers\EquipmentController;
use App\Http\Controllers\SaleController;

Route::get('/', function () {
    return redirect()->route('login');
});

Auth::routes();

Route::middleware(['auth'])->group(function () {
    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
    Route::get('/laba-rugi', [App\Http\Controllers\HomeController::class, 'labaRugi'])->name('home.labaRugi');
    
    // Routes untuk Jenis Kambing
    Route::get('/jenis', [JenisController::class, 'index'])->name('jenis.index');
    Route::post('/jenis', [JenisController::class, 'store'])->name('jenis.store');
    Route::get('/jenis/{id}/edit', [JenisController::class, 'edit'])->name('jenis.edit');
    Route::put('/jenis/{id}', [JenisController::class, 'update'])->name('jenis.update');
    Route::delete('/jenis/{id}', [JenisController::class, 'destroy'])->name('jenis.destroy');
    Route::get('/jenis/export/excel', [JenisController::class, 'exportExcel'])->name('jenis.export.excel');
    Route::get('/jenis/export/pdf', [JenisController::class, 'exportPdf'])->name('jenis.export.pdf');
    Route::get('/jenis/getData', [JenisController::class, 'getData'])->name('jenis.getData');

    // Routes untuk Kambing
    Route::get('/kambing/getNextKode', [App\Http\Controllers\KambingController::class, 'getNextKode'])->name('kambing.getNextKode');
    Route::get('/kambing/export/csv', [KambingController::class, 'exportCsv'])->name('kambing.export.csv');
    Route::get('kambing/{id}/export-pdf', [KambingController::class, 'exportPdf'])->name('kambing.export.pdf');

    // Routes untuk Barn
    Route::get('/barn/export/csv', [App\Http\Controllers\BarnController::class, 'exportCsv'])->name('barn.export.csv');
    Route::get('/barn/export/pdf', [App\Http\Controllers\BarnController::class, 'exportPdf'])->name('barn.export.pdf');
    Route::resource('barn', App\Http\Controllers\BarnController::class);

    Route::resource('jenis', JenisController::class);
    Route::resource('kambing', KambingController::class);

    // Routes untuk Health Record
    Route::get('/health_record', [App\Http\Controllers\HealthRecordController::class, 'index'])->name('health_record.index');
    Route::post('/health_record', [App\Http\Controllers\HealthRecordController::class, 'store'])->name('health_record.store');
    Route::get('/health_record/{id}', [App\Http\Controllers\HealthRecordController::class, 'show'])->name('health_record.show');
    Route::get('/health_record/{id}/edit', [App\Http\Controllers\HealthRecordController::class, 'edit'])->name('health_record.edit');
    Route::put('/health_record/{id}', [App\Http\Controllers\HealthRecordController::class, 'update'])->name('health_record.update');
    Route::delete('/health_record/{id}', [App\Http\Controllers\HealthRecordController::class, 'destroy'])->name('health_record.destroy');
    Route::get('/health_record/export/csv', [App\Http\Controllers\HealthRecordController::class, 'exportCsv'])->name('health_record.export.csv');
    Route::get('/health_record/export/pdf', [App\Http\Controllers\HealthRecordController::class, 'exportPdf'])->name('health_record.export.pdf');

    // Routes untuk Pakan
    Route::get('/feeds', [App\Http\Controllers\FeedController::class, 'index'])->name('feeds.index');
    Route::post('/feeds', [App\Http\Controllers\FeedController::class, 'store'])->name('feeds.store');
    Route::get('/feeds/{id}/edit', [App\Http\Controllers\FeedController::class, 'edit'])->name('feeds.edit');
    Route::put('/feeds/{id}', [App\Http\Controllers\FeedController::class, 'update'])->name('feeds.update');
    Route::delete('/feeds/{id}', [App\Http\Controllers\FeedController::class, 'destroy'])->name('feeds.destroy');
    Route::get('/feeds/export/excel', [App\Http\Controllers\FeedController::class, 'exportExcel'])->name('feeds.export.excel');
    Route::get('/feeds/export/pdf', [App\Http\Controllers\FeedController::class, 'exportPdf'])->name('feeds.export.pdf');

    // Routes untuk Alat dan Perlengkapan
    Route::get('/equipments', [App\Http\Controllers\EquipmentController::class, 'index'])->name('equipments.index');
    Route::post('/equipments', [App\Http\Controllers\EquipmentController::class, 'store'])->name('equipments.store');
    Route::get('/equipments/{id}/edit', [App\Http\Controllers\EquipmentController::class, 'edit'])->name('equipments.edit');
    Route::put('/equipments/{id}', [App\Http\Controllers\EquipmentController::class, 'update'])->name('equipments.update');
    Route::delete('/equipments/{id}', [App\Http\Controllers\EquipmentController::class, 'destroy'])->name('equipments.destroy');
    Route::get('/equipments/export/excel', [App\Http\Controllers\EquipmentController::class, 'exportExcel'])->name('equipments.export.excel');
    Route::get('/equipments/export/pdf', [App\Http\Controllers\EquipmentController::class, 'exportPdf'])->name('equipments.export.pdf');

    // Routes untuk Penjualan
    Route::get('/sales', [SaleController::class, 'index'])->name('sales.index');
    Route::get('/sales/create', [SaleController::class, 'create'])->name('sales.create');
    Route::post('/sales', [SaleController::class, 'store'])->name('sales.store');
    Route::get('/sales/{id}', [SaleController::class, 'show'])->name('sales.show');
    Route::get('/sales/{id}/edit', [SaleController::class, 'edit'])->name('sales.edit');
    Route::put('/sales/{id}', [SaleController::class, 'update'])->name('sales.update');
    Route::delete('/sales/{id}', [SaleController::class, 'destroy'])->name('sales.destroy');
    Route::get('/sales/getData', [SaleController::class, 'getData'])->name('sales.getData');
    Route::get('/sales/getNextKode', [SaleController::class, 'getNextKode'])->name('sales.getNextKode');
    Route::get('/sales/export/csv', [SaleController::class, 'exportCsv'])->name('sales.export.csv');
    Route::get('/sales/export/pdf', [SaleController::class, 'exportPdf'])->name('sales.export.pdf');
});
