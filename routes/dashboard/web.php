<?php

use App\Http\Controllers\Dashboard\CategoryController;
use App\Http\Controllers\Dashboard\ClientController;
use App\Http\Controllers\Dashboard\HomeController;
use App\Http\Controllers\Dashboard\OrderController;
use App\Http\Controllers\Dashboard\Client\OrderController as ClientOrderController;
use App\Http\Controllers\Dashboard\ProductController;

use App\Http\Controllers\Dashboard\UserController;

use App\Http\Controllers\Dashboard\WelcomeController;
use App\Http\Controllers\UserOrderController;
use App\Models\User;
use Illuminate\Support\Facades\Route;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;






Route::get('test',function ()  {
    $users=User::all();
    // return view('dashboard.users.index',compact('users'));
});

Route::group(['prefix' => LaravelLocalization::setLocale(), 'middleware' => ['localeSessionRedirect', 'localizationRedirect', 'localeViewPath']],
    function () {

        Route::prefix('dashboard')->name('dashboard.')->middleware(['auth'])->group(function () {

            Route::get('/', [WelcomeController::class, 'index'])->name('welcome');

            //category routes
            Route::resource('categories', CategoryController::class);

            //product routes
            Route::resource('products', ProductController::class);

            // //client routes
            Route::resource('clients', ClientController::class);
            Route::resource('clients.orders', ClientOrderController::class);

            //order routes
            Route::resource('orders', OrderController::class);
            Route::get('/orders/{order}/products', [OrderController::class, 'products'])->name('orders.products');
      
            //User_Orders routes

            Route::get('user_orders' ,[UserOrderController::class , 'index'])->name('user_orders.index');
            Route::get('user_orders/create' ,[UserOrderController::class , 'create'])->name('user_orders.create');
            Route::post('user_orders/create' ,[UserOrderController::class , 'store'])->name('user_orders.store');
            Route::delete('user_orders/{id}' ,[UserOrderController::class , 'destroy'])->name('user_orders.destroy');
            Route::get('user_orders/{id}' ,[UserOrderController::class , 'edit'])->name('user_orders.edit');
            Route::put('user_orders/{id}' ,[UserOrderController::class , 'update'])->name('user_orders.update');
            //user routes
            // Route::resource('users', [UserController::class]);
            Route::resource('users', UserController::class);

        });//end of dashboard routes
    });


