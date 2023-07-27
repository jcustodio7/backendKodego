<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SignupController;
use App\Http\Controllers\ProductController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/register', [UserController::class, 'register']);
Route::post('/login', [UserController::class, 'login']);
Route::post('/logout', [UserController::class, 'logout']);

Route::get('/customers', [UserController::class, 'index']);
Route::delete('/deleteCustomers/{id}', [UserController::class, 'destroy']);


Route::post('/addProduct', [ProductController::class, 'store']);
Route::get('/products', [ProductController::class, 'index']);
Route::get('/products/{id}', [ProductController::class, 'show']);
Route::delete('/products/{id}', [ProductController::class, 'destroy']);
Route::put('/updateProducts/{id}', [ProductController::class, 'update']);

Route::get('/customerLists', [SignupController::class, 'index']);
Route::post('/signup', [SignupController::class, 'register']);
Route::delete('/deleteCustomer/{id}', [SignupController::class, 'destroy']);

Route::post('/loginCustomer', [SignupController::class, 'login']);
Route::post('/logoutCustomer', [SignupController::class, 'logout']);

