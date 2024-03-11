<?php

use App\Http\Controllers\ClasseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RegionController;
use App\Http\Controllers\StreetConditionController;
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

//                                    api/v5
//      geojson.io
// http://localhost/api/v5/geojson/regions

Route::group(['prefix' => 'api/v5'], function () {
    Route::group(['prefix' => '/geojson'], function () {
        // Route::group(['prefix' => '/regions'], function () {
            
        //     Route::get('/',[RegionController::class, 'allRegions'] );

        //     // /{region_id}
        //     Route::get('/{id}',[RegionController::class, 'onlyRegion'] );

        // });
        // Route::get('/street-conditions', [StreetConditionController::class, 'index']);
        
        Route::apiResource('Classe', ClasseController::class);
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
