<?php

use App\Http\Controllers\DailyItemCodeController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OperatorController;
use App\Http\Controllers\InitialBarcodeController;
use App\Http\Controllers\MasterItemController;
use App\Http\Controllers\SOController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route::view('/', 'welcome');

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

// Route::view('pe/upload-files', 'upload-files')
//     ->middleware(['auth', 'verified'])
//     ->name('PE.upload-files');

Route::get('master-item', [MasterItemController::class, 'index'])->middleware(['auth', 'verified'])->name('master-item.index');

Route::post('file/upload', [FileController::class, 'upload'])->name('file.upload');
Route::delete('file/{id}/delete', [FileController::class, 'destroy'])->name('file.delete');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');


Route::get('index', [HomeController::class, 'index'])->name('index');

Route::get('operator/updatepage', [OperatorController::class, 'index'])->name('operator.index');

Route::post('/assign-item-code', [OperatorController::class, 'assignItemCode'])->name('itemcode.assign');


Route::get('/barcodes', [InitialBarcodeController::class, 'index'])->name('barcode.index');
Route::post('/barcodes/generate', [InitialBarcodeController::class, 'generate'])->name('barcode.generate');


Route::get('/manualbarcodes', [InitialBarcodeController::class, 'manualgenerate'])->name('manualbarcode.index');
Route::post('/generate-barcode', [InitialBarcodeController::class, 'generateBarcode'])->name('generate.barcode');

Route::get('/daily-item-codes/', [DailyItemCodeController::class, 'index'])->name('daily-item-code.index');
Route::post('/apply-item-code/{machine_id}', [DailyItemCodeController::class, 'applyItemCode'])->name('apply-item-code');

Route::get('/so/index', [SOController::class, 'index'])->name('so.index');
Route::get('/so/process/{docNum}', [SOController::class, 'process'])->name('so.process');
Route::post('/so/scan', [SOController::class, 'scanBarcode'])->name('so.scanBarcode');
Route::get('/update-so-data/{docNum}', [SOController::class, 'updateSoData'])->name('update.so.data');
require __DIR__ . '/auth.php';
