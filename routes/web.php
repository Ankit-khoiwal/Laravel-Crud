<?php

use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

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


Route::get('/', [UserController::class, 'index'])->name('home');
Route::get('/users', [UserController::class, 'getUsers'])->name('users.get');
Route::post('/users', [UserController::class, 'store'])->name('users.post');
