<?php

use Illuminate\Support\Facades\Route;
use  App\Http\Controllers\Admin\PostCollectionController;
use  App\Http\Controllers\Admin\PostController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::resource('/admin/collections',PostCollectionController::class);
Route::resource('/admin/posts',PostController::class);
Route::get('/crawler',[PostController::class,'crawl']);


