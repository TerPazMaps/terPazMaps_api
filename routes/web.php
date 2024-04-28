<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

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

Route::get('/2', function () {
    return view('welcome');
});

Route::get('login', [AuthController::class, 'indexLogin']);

Route::get('reset-password', [AuthController::class, 'viewResetPassword'])->name('viewResetPassword');
Route::post('reset-password', [AuthController::class, 'resetPassword'])->name('resetPassword');

