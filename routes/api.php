<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DelivererController;
use App\Http\Controllers\ImageController;
use App\Http\Controllers\NomenclatureController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

// estas rutas NO requiren de un token para poder accederse.
Route::group(['middleware' => 'api'], function () {

    Route::group(['prefix' => 'auth'], function () {
        Route::post('/login', [AuthController::class, 'login']);
        Route::post('/refresh', [AuthController::class, 'refresh']);
    });

    Route::post('/customers', [CustomerController::class, 'store'])->name('customers.store');
    Route::post('/customers/validate-data', [CustomerController::class, 'checkFirstStep'])->name('customers.validate');
});

// estas rutas requiren de un token válido para poder accederse.
Route::group(['middleware' => 'auth:api'], function () {

    Route::group(['prefix' => 'auth'], function () {
        Route::get('/customer/me', [CustomerController::class, 'me']);
        Route::get('/me', [AuthController::class, 'me']);
        Route::post('/logout', [AuthController::class, 'logout']);
    });
    // Notifications
    Route::get('/notifications', [AdminController::class, 'getNotifications'])->middleware('role:admin');
    Route::post('/notifications/mark-as-read', [AdminController::class, 'markNotification'])->middleware('role:admin');

    // Customers
    Route::get('/customers', [CustomerController::class, 'index'])->middleware('role:admin');
    Route::get('/customers/{id}', [CustomerController::class, 'show'])->middleware('role:admin');
    Route::put('/customers/verify-identification/{id}', [CustomerController::class, 'verifyIdentification'])->middleware('role:admin');
    Route::post('/customers/send-verify-email', [CustomerController::class, 'sendVerifyEmail'])->middleware('role:customer');
    Route::put('/customers/verify-email/{verification_code}', [CustomerController::class, 'verifyEmail'])->middleware('role:customer');

    // Categories
    Route::get('/categories', [CategoryController::class, 'index'])->middleware('role_or_permission:admin|categories.index');
    Route::post('/categories', [CategoryController::class, 'store'])->middleware('role:admin');

    // Nomenclatures
    Route::get('/nomenclatures', [NomenclatureController::class, 'index'])->middleware('role_or_permission:admin|categories.index');

    //Images
    Route::get('/images', [ImageController::class, 'index'])->middleware('role:admin');
    Route::post('/images', [ImageController::class, 'store'])->middleware('role:admin');
    Route::put('/images/{id}', [ImageController::class, 'update'])->middleware('role:admin');
    Route::delete('/images/{id}', [ImageController::class, 'destroy'])->middleware('role:admin');

    //Products
    Route::get('/products/most-popular', [ProductController::class, 'mostPopular'])->middleware('role_or_permission:admin|products.MorePopular');
    Route::get('/products/by-category', [ProductController::class, 'productsByCategory'])->middleware('role_or_permission:admin|products.ByCategory');
    Route::get('/products/by-search', [ProductController::class, 'searchInApp'])->middleware('role_or_permission:admin|products.BySearch');
    Route::get('/products', [ProductController::class, 'index'])->middleware('role:admin|customer');
    Route::post('/products/thumbnail', [ProductController::class, 'storeProductThumbnail'])->middleware('role:admin');
    Route::post('/products', [ProductController::class, 'store'])->middleware('role:admin');
    Route::get('/products/{id}', [ProductController::class, 'show'])->middleware('role_or_permission:admin|products.show');

    //Orders
    Route::post('/orders/checkout', [OrderController::class, 'validateCart'])->middleware('role:customer');


    Route::resource('admins', AdminController::class, ['except' => ['create', 'show', 'edit']]);
    Route::resource('deliverers', DelivererController::class, ['except' => ['create', 'show', 'edit']]);
});
