<?php


use App\Http\Controllers\Product\ProductController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\User\AuthController;
use App\Http\Controllers\Attribute\AttributeGroupController;
use App\Http\Controllers\Attribute\AttributeController;
use App\Http\Controllers\ClientController;


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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
|
| Here is where you can register authentication routes for your application.
|
*/
Route::group(['prefix' => 'auth'], static function () {
    Route::post('login', [AuthController::class, 'login']);
    Route::post('register', [AuthController::class, 'register']);
    Route::post('refresh', [AuthController::class, 'refresh']);
    Route::delete('logout', [AuthController::class, 'logout']);
});


Route::middleware('auth:sanctum')->group(static function () {
    /*
    |--------------------------------------------------------------------------
    | Attribute Routes
    |--------------------------------------------------------------------------
    |
    | Here is where you can register attribute routes for your application.
    |
    */
    Route::group(['prefix' => 'attribute'], static function () {
        Route::get('/', [AttributeController::class, 'index']);
        Route::get('/{id}', [AttributeController::class, 'show']);
        Route::post('/', [AttributeController::class, 'store']);
        Route::put('/{id}', [AttributeController::class, 'update']);
        Route::delete('/{id}', [AttributeController::class, 'destroy']);
    });

    /*
    |--------------------------------------------------------------------------
    | Attribute Group Routes
    |--------------------------------------------------------------------------
    |
    | Here is where you can register attribute group routes for your application.
    |
    */
    Route::group(['prefix' => 'attribute-group'], static function () {
        Route::get('/', [AttributeGroupController::class, 'index']);
        Route::get('/{id}', [AttributeGroupController::class, 'getAttributesByAttributeGroup']);
        Route::post('/', [AttributeGroupController::class, 'store']);
        Route::put('/{id}', [AttributeGroupController::class, 'update']);
        Route::delete('/{id}', [AttributeGroupController::class, 'destroy']);
    });

    /*
    |--------------------------------------------------------------------------
    | Product Routes
    |--------------------------------------------------------------------------
    |
    | Here is where you can register product routes for your application.
    |
    */
    Route::group(['prefix' => 'product'], static function () {
        Route::get('/', [ProductController::class, 'index']);
        Route::get('/{id}', [ProductController::class, 'show']);
        Route::post('/', [ProductController::class, 'store']);
        Route::post('/{id}', [ProductController::class, 'update']);
        Route::delete('/{id}', [ProductController::class, 'destroy']);
    });
});


Route::group(['middleware' => ['auth:sanctum']], function() {
    Route::resource('clients', ClientController::class);
});
