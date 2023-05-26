<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

    Route::resource('brand', App\Http\Controllers\BrandController::class);
    Route::resource('code', App\Http\Controllers\CodeController::class);
    Route::resource('company', App\Http\Controllers\CompanyController::class);
    Route::resource('currency', App\Http\Controllers\CurrencyController::class);
    Route::resource('item', App\Http\Controllers\ItemController::class);
    Route::resource('payment', App\Http\Controllers\PaymentController::class);
    Route::resource('service', App\Http\Controllers\ServiceController::class);
    Route::resource('tag', App\Http\Controllers\TagController::class);
    Route::resource('type', App\Http\Controllers\TypeController::class);
    Route::resource('warehouse', App\Http\Controllers\WarehouseController::class);

    Route::name('brand.')->group(function () {
        Route::controller(App\Http\Controllers\BrandController::class)->group(function () {
        });
    });

    Route::name('code.')->group(function () {
        Route::controller(App\Http\Controllers\CodeController::class)->group(function () {
        });
    });

    Route::name('company.')->group(function () {
        Route::controller(App\Http\Controllers\CompanyController::class)->group(function () {
        });
    });

    Route::name('currency.')->group(function () {
        Route::controller(App\Http\Controllers\CurrencyController::class)->group(function () {
        });
    });

    Route::name('item.')->group(function () {
        Route::controller(App\Http\Controllers\ItemController::class)->group(function () {
        });
    });

    Route::name('inventory.')->group(function () {
        Route::controller(App\Http\Controllers\InventoryController::class)->group(function () {
            Route::get('/inventory', 'index')->name('index');
        });
    });

    Route::name('payment.')->group(function () {
        Route::controller(App\Http\Controllers\PaymentController::class)->group(function () {
        });
    });

    Route::name('service.')->group(function () {
        Route::controller(App\Http\Controllers\ServiceController::class)->group(function () {
        });
    });

    Route::name('tag.')->group(function () {
        Route::controller(App\Http\Controllers\TagController::class)->group(function () {
        });
    });

    Route::name('type.')->group(function () {
        Route::controller(App\Http\Controllers\TypeController::class)->group(function () {
        });
    });

    Route::name('warehouse.')->group(function () {
        Route::controller(App\Http\Controllers\WarehouseController::class)->group(function () {
        });
    });
});