<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\JenisController;
use App\Http\Controllers\KambingController;
use App\Http\Controllers\BarnController;
use App\Http\Controllers\HealthRecordController;

Route::get('/', function () {
    return redirect()->route('login');
});

Auth::routes();

Route::middleware(['auth'])->group(function () {
    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
    
    // Routes untuk Jenis Kambing
    Route::get('/jenis', [App\Http\Controllers\JenisController::class, 'index'])->name('jenis.index');
    Route::post('/jenis', [App\Http\Controllers\JenisController::class, 'store'])->name('jenis.store');
    Route::get('/jenis/{id}/edit', [App\Http\Controllers\JenisController::class, 'edit'])->name('jenis.edit');
    Route::put('/jenis/{id}', [App\Http\Controllers\JenisController::class, 'update'])->name('jenis.update');
    Route::delete('/jenis/{id}', [App\Http\Controllers\JenisController::class, 'destroy'])->name('jenis.destroy');
    Route::get('/jenis/export/excel', [App\Http\Controllers\JenisController::class, 'exportExcel'])->name('jenis.export.excel');
    Route::get('/jenis/export/pdf', [App\Http\Controllers\JenisController::class, 'exportPdf'])->name('jenis.export.pdf');
    Route::get('/jenis/getData', [App\Http\Controllers\JenisController::class, 'getData'])->name('jenis.getData');

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
});
