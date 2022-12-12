<?php

use Illuminate\Http\Request;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\UserController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::post('/login', [UserController::class, 'login'])->name('login');
Route::post('/logout', [UserController::class, 'logout']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/products/create', [ProductController::class, 'store']);
    Route::post('/products/update/{id}', [ProductController::class, 'update']);
    Route::post('/products/delete/{id}', [ProductController::class, 'destroy']);
    Route::get('/products/get-list', [ProductController::class, 'getList']);
    Route::get('/products/search', [ProductController::class, 'search']);
    Route::get('/products/export', [ProductController::class, 'export'])->name("export-products");
});
// Route::get('/', [ProductController::class,'index'])->name('home');
// Route::get('/products', [ProductController::class,'index']);

Route::group(['prefix' => 'auth'], function () {
    //Route::post('register', 'UsersController@register');
    

});
