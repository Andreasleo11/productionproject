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
use App\Http\Controllers\BarcodeController;

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

Route::post('/dashboard/update-machine-job', [DashboardController::class, 'updateMachineJob'])->name('update.machine_job');
Route::get('/generate-barcode/{item_code}/{quantity}', [DashboardController::class, 'itemCodeBarcode'])->name('generate.itemcode.barcode');
Route::post('/process/itemproduction', [DashboardController::class, 'procesProductionBarcodes'])->name('process.productionbarcode');
Route::get('/reset-jobs', [DashboardController::class, 'resetJobs'])->name('reset.jobs');

Route::get('barcode/index', [BarcodeController::class, 'index'])->name('barcode.base.index');
Route::get('barcode/inandout/index', [BarcodeController::class, 'inandoutpage'])->name('inandout.index');
Route::get('barcode/missing/index', [BarcodeController::class, 'missingbarcodeindex'])->name('missingbarcode.index');
Route::post('barcode/missing/generate', [BarcodeController::class, 'missingbarcodegenerator'])->name('generateBarcodeMissing');

Route::post('barcode/process/save', [BarcodeController::class, 'processInAndOut'])->name('process.in.and.out');

Route::post('process/inandoutbarcode', [BarcodeController::class, 'storeInAndOut'])->name('processbarcodeinandout');
Route::get('indexbarcode', [BarcodeController::class, 'indexBarcode'])->name('barcodeindex');
Route::post('packaging-barcode-generate', [BarcodeController::class, 'generateBarcode'])->name('generatepackagingbarcode');

Route::get('barcode/list', [BarcodeController::class, 'barcodelist'])->name('list.barcode');

Route::get('barcode/latest/item', [BarcodeController::class, 'latestitemdetails'])->name('updated.barcode.item.position');

Route::get('barcode/historytable', [BarcodeController::class, 'historybarcodelist'])->name('barcode.historytable');

Route::get('/barcode/filter', [BarcodeController::class, 'filter'])->name('barcode.filter');
Route::get('barcode/latest/item', [BarcodeController::class, 'latestitemdetails'])->name('updated.barcode.item.position');
Route::get('barcode/stockall/{location?}', [BarcodeController::class, 'stockall'])->name('stockallbarcode');

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


Route::get('/initialbarcode', [InitialBarcodeController::class, 'index'])->name('barcode.index');
Route::post('/barcodes/generate', [InitialBarcodeController::class, 'generate'])->name('barcode.generate');


Route::get('/manualbarcodes', [InitialBarcodeController::class, 'manualgenerate'])->name('manualbarcode.index');
Route::post('/generate-barcode', [InitialBarcodeController::class, 'generateBarcode'])->name('generate.barcode');

Route::get('/daily-item-codes', [DailyItemCodeController::class, 'index'])->name('daily-item-code.index');
Route::post('/daily-item-code', [DailyItemCodeController::class, 'store'])->name('daily-item-code.store');
Route::get('/daily-item-code', [DailyItemCodeController::class, 'create'])->name('daily-item-code.create');
Route::get('/daily-item-codes', [DailyItemCodeController::class, 'index'])->name('daily-item-code.index');
Route::post('/apply-item-code/{machine_id}', [DailyItemCodeController::class, 'applyItemCode'])->name('apply-item-code');

Route::get('/so/index', [SOController::class, 'index'])->name('so.index');
Route::get('/so/process/{docNum}', [SOController::class, 'process'])->name('so.process');
Route::post('/so/scan', [SOController::class, 'scanBarcode'])->name('so.scanBarcode');
Route::get('/update-so-data/{docNum}', [SOController::class, 'updateSoData'])->name('update.so.data');

require __DIR__ . '/auth.php';
