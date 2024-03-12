<?php

use App\Http\Controllers\ActivitieController;
use App\Http\Controllers\ClasseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RegionController;
use App\Http\Controllers\StreetConditionController;
use App\Http\Controllers\StreetController;
use App\Http\Controllers\SubclasseController;
use App\Models\Classe;

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

//                                    api/v5
//      geojson.io
// http://localhost/api/v5/geojson/regions

Route::group(['prefix' => 'api/v5'], function () {
    Route::group(['prefix' => '/geojson'], function () {
    
        Route::apiResource('Classe', ClasseController::class);
        
        Route::apiResource('Region', RegionController::class);
        
        Route::apiResource('Street_condition', StreetConditionController::class);
        
        Route::apiResource('Subclasse', SubclasseController::class);
        
        Route::apiResource('Activitie', ActivitieController::class);
        
        Route::apiResource('Street', StreetController::class);
    
    });
});

/**
 * Classe
 * Region
 * Street_condition
 * 
 * Subclasse
 * Activitie
 * Street
 * 
 * 
 */
