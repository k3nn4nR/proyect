<?php

use Illuminate\Http\Request;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    dd('API/USER');
});

Route::get('/login',[App\Http\Controllers\Auth\LoginController::class,'api_login'])->name('api_login');

Route::middleware('auth:sanctum')->group(function () {
    Route::name('brand.')->group(function () {
        Route::controller(App\Http\Controllers\BrandController::class)->group(function () {
            Route::get('/brand', 'api_index')->name('api_index');
            Route::post('/brand', 'store')->name('api_store');
        });
    });

    Route::name('code.')->group(function () {
        Route::controller(App\Http\Controllers\CodeController::class)->group(function () {
            Route::get('/code', 'api_index')->name('api_index');
            Route::post('/code', 'store')->name('api_store');
        });
    });

    Route::name('company.')->group(function () {
        Route::controller(App\Http\Controllers\CompanyController::class)->group(function () {
            Route::get('/company', 'api_index')->name('api_index');
            Route::post('/company', 'store')->name('api_store');
        });
    });

    Route::name('currency.')->group(function () {
        Route::controller(App\Http\Controllers\CurrencyController::class)->group(function () {
            Route::get('/currency', 'api_index')->name('api_index');
            Route::post('/currency', 'store')->name('api_store');
        });
    });

    Route::name('item.')->group(function () {
        Route::controller(App\Http\Controllers\ItemController::class)->group(function () {
            Route::get('/item', 'api_index')->name('api_index');
            Route::post('/item', 'store')->name('api_store');
        });
    });

    Route::name('inventory.')->group(function () {
        Route::controller(App\Http\Controllers\InventoryController::class)->group(function () {
            Route::get('/inventory', 'api_index')->name('api_index');
            Route::get('/inventory_current_goods', 'api_current_goods')->name('api_current_goods');
        });
    });

    Route::name('payment.')->group(function () {
        Route::controller(App\Http\Controllers\PaymentController::class)->group(function () {
            Route::get('/payment', 'api_index')->name('api_index');
            Route::get('/payment/api_payments_last_month', 'api_payments_last_month')->name('api_payments_last_month');
            Route::get('/payment/api_payments_last_six_months', 'api_payments_last_six_months')->name('api_payments_last_six_months');
            Route::post('/payment', 'store')->name('api_store');
        });
    });

    Route::name('service.')->group(function () {
        Route::controller(App\Http\Controllers\ServiceController::class)->group(function () {
            Route::get('/service', 'api_index')->name('api_index');
            Route::post('/service', 'store')->name('api_store');
        });
    });

    Route::name('tag.')->group(function () {
        Route::controller(App\Http\Controllers\TagController::class)->group(function () {
            Route::get('/tag', 'api_index')->name('api_index');
            Route::post('/tag', 'store')->name('api_store');
            Route::get('/tag_current_goods', 'api_current_tag_goods')->name('api_current_tag_goods');
            Route::get('/{tag}/items', 'api_tag_items')->name('api_tag_items');
        });
    });

    Route::name('type.')->group(function () {
        Route::controller(App\Http\Controllers\TypeController::class)->group(function () {
            Route::get('/type', 'api_index')->name('api_index');
            Route::post('/type', 'store')->name('api_store');
        });
    });

    Route::name('warehouse.')->group(function () {
        Route::controller(App\Http\Controllers\WarehouseController::class)->group(function () {
            Route::get('/warehouse', 'api_index')->name('api_index');
            Route::post('/warehouse', 'store')->name('api_store');
        });
    });
});