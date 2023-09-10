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
            Route::post('/brand/{brand}/tags','store_tags')->name('store_tags');
        });
    });

    Route::name('code.')->group(function () {
        Route::controller(App\Http\Controllers\CodeController::class)->group(function () {
            Route::post('/code/{code}/tags','store_tags')->name('store_tags');
        });
    });

    Route::name('company.')->group(function () {
        Route::controller(App\Http\Controllers\CompanyController::class)->group(function () {
            Route::post('/company/{company}/tags','store_tags')->name('store_tags');
        });
    });

    Route::name('currency.')->group(function () {
        Route::controller(App\Http\Controllers\CurrencyController::class)->group(function () {
            Route::post('/currency/{currency}/tags','store_tags')->name('store_tags');
        });
    });

    Route::name('item.')->group(function () {
        Route::controller(App\Http\Controllers\ItemController::class)->group(function () {
            Route::post('/item/{item}/tags','store_tags')->name('store_tags');
        });
    });

    Route::name('inventory.')->group(function () {
        Route::controller(App\Http\Controllers\InventoryController::class)->group(function () {
            Route::get('/inventory', 'index')->name('index');
            Route::post('/inventory/{warehousse}/{search}', 'consume')->name('consume');
        });
    });

    Route::name('payment.')->group(function () {
        Route::controller(App\Http\Controllers\PaymentController::class)->group(function () {
            Route::post('/payment/{payment}/tags','store_tags')->name('store_tags');
            Route::post('/payment/{payment}/type_code','type_code')->name('type_code');
        });
    });

    Route::name('service.')->group(function () {
        Route::controller(App\Http\Controllers\ServiceController::class)->group(function () {
            Route::post('/service/{service}/tags','store_tags')->name('store_tags');
        });
    });

    Route::name('tag.')->group(function () {
        Route::controller(App\Http\Controllers\TagController::class)->group(function () {
            Route::post('/tag/{tag}/tags','store_tags')->name('store_tags');
        });
    });

    Route::name('type.')->group(function () {
        Route::controller(App\Http\Controllers\TypeController::class)->group(function () {
            Route::post('/type/{type}/tags','store_tags')->name('store_tags');
        });
    });

    Route::name('warehouse.')->group(function () {
        Route::controller(App\Http\Controllers\WarehouseController::class)->group(function () {
            Route::post('/warehouse/{warehouse}/tags','store_tags')->name('store_tags');
        });
    });
});