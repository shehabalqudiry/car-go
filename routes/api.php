<?php

use App\Http\Controllers\APIs\Account\AddressController;
use App\Http\Controllers\APIs\HomeController;
use App\Http\Controllers\APIs\Account\AuthController;
use App\Http\Controllers\APIs\Account\ProfileController;
use App\Http\Controllers\APIs\Account\WalletController;
use App\Http\Controllers\APIs\Shipments\ShipmentController;
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

Route::prefix('v1')->group(function() {
    Route::controller(AuthController::class)->group(function () {
        Route::post('/register', 'register');
        Route::post('/send_otp', 'send_otp');
        Route::post('/login', 'login');
    });

    Route::controller(HomeController::class)->group(function () {
        Route::post('/contact', 'contact');
        Route::post('/createCoupon', 'createCoupon');
        Route::get('/cities', 'cities');
        Route::get('/weights', 'weights');
        Route::get('/payment_methods', 'payment_methods');
        Route::get('/terms', 'terms');
        Route::get('/support', 'support');
    });



    Route::middleware('auth:sanctum')->group(function() {
        Route::controller(HomeController::class)->group(function () {
            Route::get('/home', 'home');
        });

        Route::controller(ShipmentController::class)->group(function () {
            Route::post('/createShipment', 'createShipment');
            Route::post('/shipments_pay', 'shipments_pay');
            Route::get('/my_shipments', 'my_shipments');
            Route::get('/tracking_shipment', 'tracking_shipment');
            Route::post('/checkCoupon', 'checkCoupon');
        });

        Route::controller(AddressController::class)->group(function () {
            Route::get('/my_addresses', 'index');
            Route::post('/new_address', 'store');
            Route::post('/update_address', 'update');
            Route::post('/destroy_address', 'destroy');
        });


        Route::controller(ProfileController::class)->group(function () {
            Route::get('/profile', 'index');
            Route::post('/profile', 'update');
            Route::get('/delete_account', 'delete_account');
        });

        Route::controller(WalletController::class)->group(function () {
            Route::get('/wallet', 'index');
            Route::get('/last_operations', 'last_operations');
            Route::post('/add_credit', 'add_credit');
        });

    });
});

