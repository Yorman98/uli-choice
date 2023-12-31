<?php

use App\Http\Controllers\Cart\CartController;
use App\Http\Controllers\Transactions\PaymentMethodsController;
use App\Http\Controllers\Transactions\TransactionsController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\User\AuthController;
use App\Http\Controllers\Attribute\AttributeGroupController;
use App\Http\Controllers\Attribute\AttributeController;
use App\Http\Controllers\ProductCategory\CategoryController;
use App\Http\Controllers\Product\ProductController;
use App\Http\Controllers\Purchase\PurchaseController;
use App\Http\Controllers\Provider\ProviderController;
use App\Http\Controllers\Product\ProductVariationController;
use App\Http\Controllers\Order\OrderController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\Budget\BudgetController;


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

/*
|--------------------------------------------------------------------------
| Cart Routes
|--------------------------------------------------------------------------
|
| Here is where you can manage the cart
|
*/
Route::group(['prefix' => 'cart'], static function () {
    Route::get('/{id}', [CartController::class, 'getActiveCartByUserId']);
    Route::get('/{id}/products', [CartController::class, 'getCartProducts']);
    Route::post('/', [CartController::class, 'addProductToCart']);
    Route::post('/update-quantity', [CartController::class, 'updateQuantityToProduct']);
    Route::delete('/{id}', [CartController::class, 'removeProductFromCart']);
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

        /*
         * Variations for a product
         */
        Route::get('/{id}/variations', [ProductVariationController::class, 'index']);
        Route::post('/{id}/variations', [ProductVariationController::class, 'updateOrCreate']);
        Route::delete('/{id}/variations/{variation_id}', [ProductVariationController::class, 'destroy']);
    });

    /*
    |--------------------------------------------------------------------------
    | Product Category Routes
    |--------------------------------------------------------------------------
    |
    | Here is where you can register category routes for your application.
    |
    */
    Route::group(['prefix' => 'category'], static function () {
        Route::get('/', [CategoryController::class, 'index']);
        Route::get('/tree', [CategoryController::class, 'getAllCategoriesTree']);
        Route::get('/{id}', [CategoryController::class, 'show']);
        Route::post('/', [CategoryController::class, 'store']);
        Route::put('/{id}', [CategoryController::class, 'update']);
        Route::delete('/{id}', [CategoryController::class, 'destroy']);
    });

    /*
    |--------------------------------------------------------------------------
    | Purchase Routes
    |--------------------------------------------------------------------------
    |
    | Here is where you can register purchase routes for your application.
    |
    */
    Route::group(['prefix' => 'purchase'], static function () {
        Route::get('/', [PurchaseController::class, 'index']);
        Route::get('/provider/{id}', [PurchaseController::class, 'getPurchasesByProviderID']);
        Route::get('/{id}', [PurchaseController::class, 'show']);
        Route::post('/', [PurchaseController::class, 'store']);
        Route::put('/{id}', [PurchaseController::class, 'update']);
        Route::delete('/{id}', [PurchaseController::class, 'destroy']);
    });

    /*
    |--------------------------------------------------------------------------
    | Provider Routes
    |--------------------------------------------------------------------------
    |
    | Here is where you can register provider routes for your application.
    |
    */
    Route::group(['prefix' => 'provider'], static function () {
        Route::get('/', [ProviderController::class, 'index']);
        Route::get('/{id}', [ProviderController::class, 'show']);
        Route::post('/', [ProviderController::class, 'store']);
        Route::put('/{id}', [ProviderController::class, 'update']);
        Route::delete('/{id}', [ProviderController::class, 'destroy']);
    });

    /*
    |--------------------------------------------------------------------------
    | Payment Methods Routes
    |--------------------------------------------------------------------------
    |
    | Here is where you can register payment methods routes for your application.
    |
    */
    Route::group(['prefix' => 'payment-methods'], static function () {
        Route::get('/', [PaymentMethodsController::class, 'index']);
        Route::post('/', [PaymentMethodsController::class, 'store']);
        Route::delete('/{id}', [PaymentMethodsController::class, 'softDelete']);
    });

    /*
    |--------------------------------------------------------------------------
    | Transactions Routes
    |--------------------------------------------------------------------------
    |
    | Here is where you can register transactions routes for your application.
    |
    */
    Route::group(['prefix' => 'transactions'], static function () {
        Route::get('/{id}', [TransactionsController::class, 'getTransactionsByOrderId']);
        Route::post('/', [TransactionsController::class, 'store']);
        Route::put('/{id}', [TransactionsController::class, 'update']);
        Route::delete('/{id}', [TransactionsController::class, 'destroy']);
    });

    /*
    |--------------------------------------------------------------------------
    | Orders Routes
    |--------------------------------------------------------------------------
    |
    | Here is where you can register orders routes for your application.
    |
    */
    Route::group(['prefix' => 'orders'], static function () {
        Route::get('/', [OrderController::class, 'getOrders']);
        Route::get('/{id}', [OrderController::class, 'getOrder']);
        Route::post('/', [OrderController::class, 'createOrder']);
        Route::put('/{id}', [OrderController::class, 'updateOrder']);
        Route::delete('/{id}', [OrderController::class, 'deleteOrder']);
        // Download PDF
        Route::get('/{id}/invoice', [OrderController::class, 'generateInvoiceByOrderId']);
    });

    /*
    |--------------------------------------------------------------------------
    | Client Routes
    |--------------------------------------------------------------------------
    |
    | Here is where you can register user routes for your application.
    |
    */
    Route::resource('clients', ClientController::class)->except(['create', 'edit']);

    /*
    |--------------------------------------------------------------------------
    | Budget Routes
    |--------------------------------------------------------------------------
    |
    | Here is where you can register budget routes for your application.
    |
    */
    Route::resource('budgets', BudgetController::class)->except(['create', 'edit', 'update']);
    Route::put('budgets/{id}/from-user', [BudgetController::class, 'updateFromUser']);
    Route::put('budgets/{id}', [BudgetController::class, 'update'])->middleware('isAdmin');
});

// Export products to CSV
Route::get('export-inventory', [ProductController::class, 'exportInventory']);
