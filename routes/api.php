<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\CourseController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\User\OrderController;



/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post("register", [UserController::class, "register"]);
Route::post("login", [UserController::class, "login"]);
Route::get('courses', [CourseController::class, "all"]);
Route::get('products', [ProductController::class, "index"]);
Route::post('refresh', [UserController::class, 'refreshWithToken']);

Route::group(["middleware" => ["auth:api"]], function() {

    //ADMIN PERMISSIONS
    Route::group([
        'prefix' => 'admin',
        'middleware' => 'is_admin',
        'as' => 'admin.',
    ], function() {
        Route::controller(\App\Http\Controllers\Api\Admin\ProductController::class)->group(function () {
            Route::get('/products', 'index');
            Route::post('/products/imageUpload', 'imageUpload');
            Route::post('/products', 'store');
            Route::get('/products/{id}', 'show');
            Route::put('/products/{id}', 'update');
            Route::delete('/products/{id}', 'destroy');
        });

        Route::controller(\App\Http\Controllers\Api\Admin\OrderController::class)->group(function () {
            Route::get('/orders', 'index');
            Route::get('/orders/{id}', 'show');
            Route::delete('/orders/{id}', 'destroy');
        });
    });

    //NORMAL USER PERMISSIONS
    Route::group([
        'prefix' => 'user',
        'as' => 'user.',
    ], function() {
        Route::controller(ProductController::class)->group(function () {
            Route::get('/products', 'index');
        });

        Route::controller(OrderController::class)->group(function () {
            Route::get('/orders', 'index');
            Route::get('/orders/{id}', 'show');
            Route::post('/orders', 'store');
        });
    });

    //ROUTES FOR ALL LOGGEDIN USERS
    Route::get("profile", [UserController::class, "profile"]);
    Route::get("logout", [UserController::class, "logout"]);
});

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

//CORS TEST
// Route::middleware(['cors'])->group(function () {
//     Route::post('/hogehoge', 'Controller@hogehoge');
// });

