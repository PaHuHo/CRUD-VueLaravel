<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('/', function () {
//     return redirect()->route('home');
// });
Route::get('/', [ProductController::class,'index'])->name('home');
Route::get('/products', [ProductController::class,'index']);
Route::post('/products/create', [ProductController::class,'store']);
Route::post('/products/update/{id}', [ProductController::class,'update']);
Route::post('/products/delete/{id}', [ProductController::class,'destroy']);
Route::get('/products/get-list', [ProductController::class,'getList']);
Route::get('/products/search', [ProductController::class,'search']);


