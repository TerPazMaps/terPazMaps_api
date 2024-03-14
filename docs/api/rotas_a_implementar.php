<?php

Route::group(['prefix' => 'api/v4'], function () {
    Route::group(['prefix' => '/geojson'], function () {
        Route::group(['prefix' => '/regions'], function () {
            // OK
            // Route::get('/', function () {
            //     $regions = Region::orderBy('id')
            //         ->get()
            //         ->map(function ($region) {
            //             $geojson_region = [
            //                 "type" => "Feature",
            //                 "geometry" => json_decode($region->geometry),
            //                 "properties" => [
            //                     "ID" => $region->id,
            //                     "Nome" => $region->name,
            //                     "Centro" => json_decode($region->center)
            //                 ]
            //             ];
            //             return $geojson_region;
            //         });

            //     $geojson = [
            //         "type" => "FeatureCollection",
            //         "features" => $regions
            //     ];
            //     return $geojson;
            // });

            // OK
            // Route::get('/{id}', function ($id) {
            //     $region = Region::findOrFail($id);

            //     $geojson = [
            //         "type" => "Feature",
            //         "geometry" => json_decode($region->geometry),
            //         "properties" => [
            //             "ID" => $region->id,
            //             "Nome" => $region->name,
            //             "Centro" => json_decode($region->center)
            //         ]
            //     ];
            //     return $geojson;
            // });

            // DESATIVADA (deve ter sido subtituida pela /geojson/activities)
            // Route::get('/{id}/activities', function ($id, Request $request) {
            //     $activities = Activity::orderBy('id')
            //         ->where('region_id', $id);

            //     if ($request->name) {
            //         $activities = $activities->where('name', '<>', '')
            //             ->where(function ($query) use ($request) {
            //                 $query = $query
            //                 ->where('name', 'like', $request->name . '%')
            //                 ->orWhere('name', 'like', '% ' . $request->name . '%');
            //             });
            //     }

            //     if ($request->subclasses)
            //         $activities = $activities->whereIn('subclass_id', $request->subclasses);

            //     $activities = $activities->get();


            //     if ($request->clean) {
            //         $activities = $activities
            //             ->map(function ($activity) {
            //                 $geojson_activity = [
            //                     "type" => "Feature",
            //                     "geometry" => json_decode($activity->geometry),
            //                     "properties" => [
            //                         "ID Geral" => $activity->id,
            //                         "Nome" => $activity->name ?? '',
            //                         "ID Subclasse" => $activity->subclass->id,
            //                     ]
            //                 ];

            //                 return $geojson_activity;
            //             }); 
            //     } else {
            //         $activities = $activities
            //             ->map(function ($activity) {
            //                 $geojson_activity = [
            //                     "type" => "Feature",
            //                     "geometry" => json_decode($activity->geometry),
            //                     "properties" => [
            //                         "ID Geral" => $activity->id,
            //                         "Nome" => $activity->name ?? '',
            //                         "Classe" => $activity->subclass->class->name,
            //                         "Sub-classe" => $activity->subclass->name,
            //                         "Bairro" => $activity->region->name,
            //                         "Nível" => $activity->level
            //                     ]
            //                 ];

            //                 return $geojson_activity;
            //             });
            //     }

            //     $geojson = [
            //         "type" => "FeatureCollection",
            //         "features" => $activities
            //     ];

            //     if ($request->counter) {
            //         $geojson["counter"] = count($activities);
            //     } 

            //     return $geojson;
            // });
        });

        // todas as activities(suporta pesquisa) OK
        // Route::group(['prefix' => '/activities'], function () {
        //     Route::get('/', function (Request $request) {
        //         $activities = Activity::orderBy('id');

        //         if ($request->regions)
        //             $activities = $activities->whereIn('region_id', $request->regions);

        //         if ($request->subclasses)
        //             $activities = $activities->whereIn('subclass_id', $request->subclasses);

        //         if ($request->ids)
        //             $activities = $activities->whereIn('id', $request->ids);

        //         $activities = $activities->get();

        //         if ($request->only_references) {
        //             $activities = $activities
        //                 ->map(function ($activity) {
        //                     $geojson_activity = [
        //                         "type" => "Feature",
        //                         "geometry" => json_decode($activity->geometry),
        //                         "properties" => [
        //                             "ID Geral" => $activity->id,
        //                             "Nome" => $activity->name ?? '',
        //                             "ID Subclasse" => $activity->subclass->id,
        //                             "ID Bairro" => $activity->region->id,
        //                             "Nível" => $activity->level
        //                         ]
        //                     ];

        //                     return $geojson_activity;
        //                 });
        //         } else {
        //             $activities = $activities
        //                 ->map(function ($activity) {
        //                     $geojson_activity = [
        //                         "type" => "Feature",
        //                         "geometry" => json_decode($activity->geometry),
        //                         "properties" => [
        //                             "ID Geral" => $activity->id,
        //                             "Nome" => $activity->name ?? '',
        //                             "Classe" => $activity->subclass->class->name,
        //                             "Sub-classe" => $activity->subclass->name,
        //                             "Bairro" => $activity->region->name,
        //                             "Nível" => $activity->level
        //                         ]
        //                     ];

        //                     return $geojson_activity;
        //                 });
        //         }

        //         $geojson = [
        //             "type" => "FeatureCollection",
        //             "features" => $activities
        //         ];

        //         return $geojson;
        //     });
        // });
    });

    // todas as activities(sem formato geojson) DESATIVADA
    // Route::group(['prefix' => '/activities'], function () {
    //     Route::get('/', function (Request $request) {
    //         $activities = Activity::orderBy('id');

    //         if ($request->regions)
    //             $activities = $activities->whereIn('region_id', $request->regions);

    //         if ($request->subclasses)
    //             $activities = $activities->whereIn('subclass_id', $request->subclasses);

    //         if ($request->name) {
    //             $activities = $activities->where('name', '<>', '')
    //                 ->where(function ($query) use ($request) {
    //                     $query = $query
    //                         ->where('name', 'like', $request->name . '%')
    //                         ->orWhere('name', 'like', '% ' . $request->name . '%');
    //                 });
    //         }

    //         $activities = $activities->paginate(10);

    //         return $activities;
    //     });
    // });


    Route::group(['prefix' => '/classes'], function () {

        // retorna as classes- id, name, cor  OK
        // Route::get('/', function () {
        //     $classes = ActivityClass::orderBy('id')->get();
        //     return $classes;
        // });

        // classes por ID + JOIN system_files OK
        // Route::get('/{id}/subclasses', function ($id) {
        //     $subclasses = ActivitySubclass::orderBy('id')
        //         ->where('class_id', $id)
        //         ->with('related_icon')->paginate(100);
        //     return $subclasses;
        // });
    });

    // todas as subclasses + JOIN system_files   OK
    // Route::group(['prefix' => '/subclasses'], function () {

    //     Route::get('/', function (Request $request) {
    //         $subclasses = ActivitySubclass::orderBy('id');

    //         if ($request->name)
    //             $subclasses = $subclasses->where('name', 'like', $request->name . '%')
    //                 ->orWhere('name', 'like', '% ' . $request->name . '%')
    //                 ->orWhere('name', 'like', '%(' . $request->name . '%');

    //         $subclasses = $subclasses->with('related_icon')->paginate(100);

    //         return $subclasses;
    //     });
    // });
});