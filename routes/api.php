<?php

use App\Http\Controllers\ProductController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    Route::get('/product', [ProductController::class, 'allProducts']);
    Route::get('/product/{id}', [ProductController::class, 'getProduct']);
    Route::post('/product', [ProductController::class, 'save']);
    Route::post('/product/{id}', [ProductController::class, 'update']);
    Route::post('update-inventory', [ProductController::class, 'updateInventory']);
    Route::delete('/product/{id}', [ProductController::class, 'destroy']);

    Route::get('/sales', [SaleController::class, 'fetchSales']);
    Route::post('/process-sale', [SaleController::class, 'processSale']);

    Route::get('/inventory-report', [ReportController::class, 'inventoryReport']);
    Route::get('/sales-report', [ReportController::class, 'salesReport']);
    Route::get('/detailed-sales-report', [ReportController::class, 'detailedSalesReport']);
    Route::get('/daily-sales-summary', [ReportController::class, 'dailySalesSummary']);
    Route::get('/monthly-sales-summary', [ReportController::class, 'monthlySalesSummary']);
    Route::get('/sales-by-payment-mode', [ReportController::class, 'salesByPaymentMode']);
    Route::get('/best-selling-product', [ReportController::class, 'bestSellingProduct']);
    Route::get('/low-stock-alert', [ReportController::class, 'lowStockAlert']);
    Route::get('/sales-by-product', [ReportController::class, 'salesByProduct']);
    Route::get('/daily-sales-trend-analysis', [ReportController::class, 'dailySalesTrendAnalysis']);
    Route::get('/monthly-sales-trend-analysis', [ReportController::class, 'monthlySalesTrendAnalysis']);
    // Route::post('/inventory-report', [ReportController::class, 'inventoryReport']);
});

Route::post('/register', [UserController::class, 'registerUser']);
Route::post('/login', [UserController::class, 'loginUser']);
