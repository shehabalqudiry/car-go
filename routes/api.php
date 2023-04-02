<?php

use App\Http\Controllers\APIs\Account\AddressController;
use App\Http\Controllers\APIs\HomeController;
use App\Http\Controllers\APIs\Account\AuthController;
use App\Http\Controllers\APIs\Account\ProfileController;
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
        Route::get('/home', 'home');
        Route::post('/contact', 'contact');
    });



    Route::middleware('auth:sanctum')->group(function() {
        Route::controller(ShipmentController::class)->group(function () {
            Route::post('/createShipment', 'createShipment');
            Route::get('/my_shipments', 'my_shipments');
        });

        Route::controller(AddressController::class)->group(function () {
            Route::get('/my_addresses', 'index');
            Route::post('/new_address', 'store');
        });


        Route::controller(ProfileController::class)->group(function () {
            Route::get('/profile', 'index');
            Route::post('/profile', 'update');
        });
    });
});

