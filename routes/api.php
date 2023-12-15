<?php

use App\Http\Controllers\AllocateDelayController;
use App\Http\Controllers\ReportDelayController;
use App\Http\Controllers\VendorsDelaysReportController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::post('orders/{order}/report-delay', ReportDelayController::class)->name('orders.report.delay');
Route::patch('delays/allocate', AllocateDelayController::class)->name('delays.allocate');
Route::get('vendors/delays-report', VendorsDelaysReportController::class)->name('vendors.delays.report');
