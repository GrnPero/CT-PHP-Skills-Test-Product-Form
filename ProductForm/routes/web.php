<?php

use Illuminate\Support\Facades\Route;
// To use the ProductController class
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

// Returns all Products
Route::get('/', [ProductController::class, 'index']);

// Adds product to the database
Route::post('/addProduct', [ProductController::class, 'addProduct']);
