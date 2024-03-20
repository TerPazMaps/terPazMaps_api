<?php

use App\Models\Classe;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\IconController;
use App\Http\Controllers\ClasseController;
use App\Http\Controllers\RegionController;
use App\Http\Controllers\StreetController;
use App\Http\Controllers\ActivitieController;
use App\Http\Controllers\SubclasseController;
use App\Http\Controllers\StreetConditionController;
use App\Models\Subclasse;

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
    return view('streets');
});

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
        
        Route::apiResource('street', StreetController::class);

        Route::apiResource('icon', IconController::class);
    
    });
});
