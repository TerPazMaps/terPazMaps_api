<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\IconController;
use App\Http\Controllers\ClasseController;
use App\Http\Controllers\RegionController;
use App\Http\Controllers\StreetController;
use App\Http\Controllers\ServicesController;
use App\Http\Controllers\ActivitieController;
use App\Http\Controllers\FeedbackActivitieController;
use App\Http\Controllers\FeedbackStreetController;
use App\Http\Controllers\SubclasseController;
use App\Http\Controllers\StreetConditionController;
use App\Http\Controllers\UserCustomMapController;

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

Route::get('/terpazmaps', function () {
    $baseUrl = config('app.url');
    return view('index', compact('baseUrl'));
})->name('index');

//                                    api/v5
//      geojson.io
// http://localhost/api/v5/geojson/regions

Route::group(['prefix' => 'api/v5'], function () {
    Route::group(['prefix' => '/geojson'], function () {

        Route::apiResource('classes', ClasseController::class);
        Route::get('/classes/{id}/subclasses', [ClasseController::class, 'getSubclassesByClass']);

        Route::apiResource('regions', RegionController::class);
        Route::get('/regions/{id}/streets', [RegionController::class, 'getStreetsByRegion']);
        Route::get('/regions/{id}/icons', [RegionController::class, 'getIconsByRegion']);
        Route::get('/regions/{id}/activities', [RegionController::class, 'getActivitiesByRegion']);

        Route::apiResource('street_condition', StreetConditionController::class);

        Route::apiResource('subclasse', SubclasseController::class);

        Route::apiResource('activitie', ActivitieController::class);

        // 11 - consultar arae de influencia de atividades novas no mapa
        // PostgreSQL: ST_DistanceSphere()
        // MySQL:      ST_Distance_Sphere()
        Route::get('/services/activities-nearbyPG',          [ServicesController::class, 'getActivitiesNearbyPG']);
        Route::get('/services/activities-nearbyMS',          [ServicesController::class, 'getActivitiesNearbyMS']);
        
        // 12 - consultar escolas com hospitais proximos
        // PostgreSQL: ST_DistanceSphere()
        // MySQL:      ST_Distance_Sphere()
        Route::get('/services/points-of-interestPG',         [ServicesController::class, 'getPointsOfInterestPG']);
        Route::get('/services/points-of-interestMS',         [ServicesController::class, 'getPointsOfInterestMS']);
        
        // 14 - Consultar atividades em áreas de difícil acesso
        // PostgreSQL: ST_DWithin()
        // MySQL:      ST_Distance()
        Route::get('/services/difficult-access-activitiesPG',[ServicesController::class, 'getDifficultAccessActivitiesPG']);
        Route::get('/services/difficult-access-activitiesMS',[ServicesController::class, 'getDifficultAccessActivitiesMS']);
        
        // 15 - consultar atividades que estejam dentro de um raio X
        // PostgreSQL: ST_Union(ST_Buffer())
        // MySQL:      ST_Buffer() e ST_Union()
        Route::get('/services/bufferSumPG',                  [ServicesController::class, 'getBufferSumPG']);
        Route::get('/services/bufferSumMS',                  [ServicesController::class, 'getBufferSumMS']);
       
        Route::get('/services/length-street', [ServicesController::class, 'getlengthStreet']);
        Route::get('/services/distance', [ServicesController::class, 'getDistance']);
        Route::get('/services/buffer', [ServicesController::class, 'getBuffer']);

        Route::apiResource('street', StreetController::class);

        Route::apiResource('icon', IconController::class);
        
        Route::apiResource('user-custom-maps', UserCustomMapController::class)->middleware('jwt.auth');
        Route::apiResource('user-feedback-activitie', FeedbackActivitieController::class)->middleware('jwt.auth');
        Route::apiResource('user-feedback-street', FeedbackStreetController::class)->middleware('jwt.auth');
    });

    Route::post('login', [AuthController::class, 'login'])->name('login');
    Route::post('register', [AuthController::class, 'register'])->name('register');
    Route::post('logout', [AuthController::class, 'logout'])->middleware('jwt.auth');
    Route::post('refresh', [AuthController::class, 'refresh'])->middleware('jwt.auth');
    Route::post('me', [AuthController::class, 'me'])->middleware('jwt.auth');

    Route::get('send-password-reset-notification', [AuthController::class, 'viewSendPasswordResetNotification'])->name('send-password-reset-notification');
    Route::post('send-password-reset-notification', [AuthController::class, 'sendPasswordResetNotification'])->name('send-password-reset-notification');

});

