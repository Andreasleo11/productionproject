<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PEController;
use App\Http\Controllers\OperatorController;
use App\Http\Controllers\InitialBarcodeController;

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

Route::view('/', 'welcome');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');


Route::get('index', [HomeController::class, 'index'])->name('index');

Route::get('operator/updatepage', [OperatorController::class, 'index'])->name('operator.index');

Route::post('/assign-item-code', [OperatorController::class, 'assignItemCode'])->name('itemcode.assign');


Route::get('/barcodes', [InitialBarcodeController::class, 'index'])->name('barcode.index');
Route::post('/barcodes/generate', [InitialBarcodeController::class, 'generate'])->name('barcode.generate');
require __DIR__.'/auth.php';

