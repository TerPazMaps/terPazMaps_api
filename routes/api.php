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
use App\Http\Controllers\ServicesController;
use App\Http\Controllers\SubclasseController;
use App\Http\Controllers\StreetConditionController;


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

Route::get('/', function () {
    return view('welcome');
});


Route::get('/maps', function () {
    $baseUrl = config('app.url');
    return view('streets', compact('baseUrl'));
})->middleware('jwt.auth');

Route::get('/icons', function () {
    $baseUrl = config('app.url');
    return view('icons', compact('baseUrl'));
});

Route::get('/terpazmaps', function () { 
    $baseUrl = config('app.url');
    return view('index', compact('baseUrl'));
})->name('index');

//                                    api/v5
//      geojson.io
// http://localhost/api/v5/geojson/regions

Route::group(['prefix' => 'api/v5'], function () {
    Route::group(['prefix' => '/geojson'], function () {
        
        Route::apiResource('classe', ClasseController::class);
        Route::get('/classe/{id}/subclasses', [ClasseController::class, 'getSubclassesByClass']);
        
        Route::apiResource('region', RegionController::class);
        Route::get('/region/{id}/streets', [RegionController::class, 'getStreetsByRegion']);
        Route::get('/region/{id}/icons', [RegionController::class, 'getIconsByRegion']);
        
        Route::apiResource('street_condition', StreetConditionController::class);
        
        Route::apiResource('subclasse', SubclasseController::class);
        
        Route::apiResource('activitie', ActivitieController::class);
        
        Route::get('/services/activities-nearby', [ServicesController::class, 'getActivitiesbyArea']);
        Route::get('/services/activities-nearby2', [ServicesController::class, 'getActivitiesbyArea2']);
        Route::get('/services/distance', [ServicesController::class, 'getDistance']);
        Route::get('/services/distance2', [ServicesController::class, 'getDistance2']);
        Route::get('/services/points-of-interest', [ServicesController::class, 'getEscolas']);
        Route::get('/services/points-of-interest2', [ServicesController::class, 'getEscolas2']);
        Route::get('/services/length-street', [ServicesController::class, 'getLengthStreet']);
        Route::get('/services/length-street2', [ServicesController::class, 'getlengthStreet2']);
        
        Route::apiResource('street', StreetController::class);

        Route::apiResource('icon', IconController::class);
        
    });

    Route::post('login', [AuthController::class, 'login'])->name('login');
    Route::post('register', [AuthController::class, 'register'])->name('register');
    Route::post('logout', [AuthController::class, 'logout'])->middleware('jwt.auth');
    Route::post('refresh', [AuthController::class, 'refresh'])->middleware('jwt.auth');
    Route::post('me', [AuthController::class, 'me'])->middleware('jwt.auth');
});
