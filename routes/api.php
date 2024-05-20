<?php

use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\categoriescontroller;
use App\Http\Controllers\productcontroller;
use App\Http\Middleware\AdminMiddleware;
use App\Http\Middleware\UserMiddleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Laravel\Passport\Http\Controllers\AccessTokenController;
use Laravel\Passport\Http\Controllers\AuthorizationController;
use Laravel\Passport\Http\Controllers\ClientController;
use Laravel\Passport\Http\Controllers\ScopeController;

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

Route::get('auth/google', [GoogleController::class, 'redirectToGoogle']);
Route::get('auth/google/callback', [GoogleController::class, 'handleGoogleCallback']);
Route::post('/login', [AuthController::class, 'login']);
Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});



Route::post('/oauth/token', [AccessTokenController::class, 'issueToken']);
Route::post('/oauth/token/refresh', [AccessTokenController::class, 'refreshToken']);
Route::delete('/oauth/token', [AccessTokenController::class, 'revokeToken']);

Route::get('/oauth/authorize', [AuthorizationController::class, 'authorize']);
Route::post('/oauth/authorize', [AuthorizationController::class, 'approve']);

Route::post('/oauth/clients', [ClientController::class, 'store']);
Route::get('/oauth/clients', [ClientController::class, 'forUser']);
Route::put('/oauth/clients/{client_id}', [ClientController::class, 'update']);
Route::delete('/oauth/clients/{client_id}', [ClientController::class, 'destroy']);

Route::get('/oauth/scopes', [ScopeController::class, 'all']);


// Admin Middleware
Route::middleware([AdminMiddleware::class])->group(function () {
    // store API
    Route::post('/product',[productcontroller::class,"store"]);
    Route::post('/categories',[categoriescontroller::class,"store"]);
    // Routes for Product
    Route::get('/product', [ProductController::class, "showall"]);
    Route::get('/product/id/{id}', [ProductController::class, "showbyid"]);
    Route::get('/product/categories/{id}', [ProductController::class, "showbycategories"]);
    Route::put('/product/update/{id}', [ProductController::class, "update"]);
    Route::delete('/product/{id}', [ProductController::class, "delete"]);

    // Routes for Categories
    Route::get('/categories', [CategoriesController::class, "showall"]);
    Route::get('/categories/id/{id}', [CategoriesController::class, "showbyid"]);
    Route::put('/categories/update/{id}', [CategoriesController::class, "update"]);
    Route::delete('/categories/{id}', [CategoriesController::class, "delete"]);
});

// User Middleware
Route::middleware([UserMiddleware::class])->group(function () {
    // store API
    Route::post('/product',[productcontroller::class,"store"]);
    Route::post('/categories',[categoriescontroller::class,"store"]);
    // Routes for Product
    Route::get('/product', [ProductController::class, "showall"]);
    Route::get('/product/{id}', [ProductController::class, "showbyid"]);
    Route::get('/product/categories/{id}', [ProductController::class, "showbycategories"]);
    Route::put('/product/{id}', [ProductController::class, "update"]);
    Route::delete('/product/{id}', [ProductController::class, "delete"]);

});