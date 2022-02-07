<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\RegisterController;
use App\Http\Controllers\Modules\Category\CategoryController;
use App\Http\Controllers\Modules\Product\ProductController;

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

Route::post('/register', [RegisterController::class, 'register']);
Route::post('/admin/login', [RegisterController::class, 'login']);

Route::middleware('auth:api')->group(function () {
	 Route::get('/getuser', [RegisterController::class, 'getuser']);
	  Route::get('/admin/logout',   [RegisterController::class, 'logout']);
	// user-category
	Route::post('/category-add',[CategoryController::class,'add'])->name('category.add');
	Route::get('/category-details/{id}',[CategoryController::class,'show'])->name('category.details');
	Route::post('/category-update',[CategoryController::class,'update'])->name('category.update');
	Route::get('/category-delete/{id}',[CategoryController::class,'delete'])->name('category.delete');
	Route::get('/category-status/{id}',[CategoryController::class,'status'])->name('category.status');

	// user-sub-category 
	Route::get('/category-fecth',[CategoryController::class,'categoryFetch'])->name('category.fecth');
	Route::post('/sub-category-add',[CategoryController::class,'subcategoryadd'])->name('sub.category.add');
	Route::get('/sub-category-details/{id}',[CategoryController::class,'subcategoryshow'])->name('sub.category.show');
	Route::post('/sub-category-update',[CategoryController::class,'subcategoryupdate'])->name('sub.category.update');
	Route::get('/sub-category-status/{id}',[CategoryController::class,'subcategorystatus'])->name('sub.category.status');
	Route::get('/sub-category-delete/{id}',[CategoryController::class,'subcategorydelete'])->name('sub.category.delete');


	// user-product-add 
	Route::get('/get-product-category',[ProductController::class,'productCategory'])->name('user.get.product.category');
	Route::post('/get-sub-category',[ProductController::class,'getsubCategory'])->name('user.get.sub.category');
	Route::post('/products-add',[ProductController::class,'addProducts'])->name('user.product.add');
	Route::get('/all-products',[ProductController::class,'allProducts'])->name('user.product.display');

});

// Sayan

//Route::get('/getuser', [RegisterControl