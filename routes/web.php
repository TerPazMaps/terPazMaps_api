<?php

use App\Models\Classe;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\IconController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ClasseController;
use App\Http\Controllers\RegionController;
use App\Http\Controllers\StreetController;
use App\Http\Controllers\ActivitieController;
use App\Http\Controllers\SubclasseController;
use App\Http\Controllers\StreetConditionController;

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
