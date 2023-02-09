<?php

use App\Http\Controllers\PostController;
use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return view('welcome');
});

Route::get('download/{post}', [PostController::class, 'download']);
Route::get('downloads', [PostController::class, 'downloads']);
Route::get('res-image/{post}', [PostController::class, 'resImage']);
Route::resource('posts', PostController::class);