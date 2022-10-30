<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BuyerController;
use App\Http\Controllers\StyleController;

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

//[buyer routes start]
    Route::get('/buyer-list', [BuyerController::class, 'index'])->name('buyer.list')->middleware('auth:sanctum');
    Route::get('/buyer-create', [BuyerController::class, 'create'])->name('buyer.create')->middleware('auth:sanctum');
    Route::post('/buyer-store', [BuyerController::class, 'store'])->name('buyer.store')->middleware('auth:sanctum');
    Route::get('/buyer-edit/{id}', [BuyerController::class, 'edit'])->name('buyer.edit')->middleware('auth:sanctum');
    Route::get('/buyer-show/{id}', [BuyerController::class, 'show'])->name('buyer.show')->middleware('auth:sanctum');
    Route::post('/buyer-update', [BuyerController::class, 'update'])->name('buyer.update')->middleware('auth:sanctum');
    Route::get('/buyer-delete/{id}', [BuyerController::class, 'destroy'])->name('buyer.delete')->middleware('auth:sanctum');
    Route::post('/buyer-search', [BuyerController::class, 'search'])->name('buyer.search')->middleware('auth:sanctum'); 
    Route::get('/buyer-list-all', [BuyerController::class, 'index_all'])->name('buyer.list.all')->middleware('auth:sanctum');
    Route::post('/buyer-search-all', [BuyerController::class, 'search_all'])->name('buyer.search.all')->middleware('auth:sanctum'); 
//[buyer routes end]

//[style routes start]
    Route::get('/style-list', [StyleController::class, 'index'])->name('style.list')->middleware('auth:sanctum');
    Route::get('/style-create', [StyleController::class, 'create'])->name('style.create')->middleware('auth:sanctum');
    Route::post('/style-store', [StyleController::class, 'store'])->name('style.store')->middleware('auth:sanctum');
    Route::get('/style-edit/{id}', [StyleController::class, 'edit'])->name('style.edit');
    Route::get('/style-show/{id}', [StyleController::class, 'show'])->name('style.show')->middleware('auth:sanctum');
    Route::post('/style-update', [StyleController::class, 'update'])->name('style.update')->middleware('auth:sanctum');
    Route::get('/style-delete/{id}', [StyleController::class, 'destroy'])->name('style.delete')->middleware('auth:sanctum');
    Route::get('/style-list-delete/{id}', [StyleController::class, 'destroy_item'])->name('style.delete.item')->middleware('auth:sanctum');
    Route::post('/style-search', [StyleController::class, 'search'])->name('style.search')->middleware('auth:sanctum'); 
    Route::get('/style-list-all', [StyleController::class, 'index_all'])->name('style.list.all')->middleware('auth:sanctum');
    Route::post('/style-search-all', [StyleController::class, 'search_all'])->name('style.search.all')->middleware('auth:sanctum'); 
    Route::post('/style-store-more', [StyleController::class, 'store_more'])->name('style.store.more')->middleware('auth:sanctum');
//[style routes end]

