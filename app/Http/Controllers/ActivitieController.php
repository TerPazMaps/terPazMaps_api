<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Activitie;
use Illuminate\Http\Request;
use App\Services\ApiServices;
use App\Services\RedisService;
use App\Services\ActivitieService;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Cache;
use App\Http\Requests\StoreActivitieRequest;
use App\Http\Requests\UpdateActivitieRequest;

class ActivitieController extends Controller
{
    protected $activitieService;
    protected $redisService;

    public function __construct()
    {
        $this->activitieService = new ActivitieService();
        $this->redisService = new RedisService();
    }

    /**
     * Display a listing of the resource.
     */
    // http://127.0.0.1:8000/api/v5/geojson/activities
    public function index(Request $request)
    {
        try {
            $startTime = microtime(true);
            $namesRequest = ['regions', 'subclasses', 'ids', 'only_references'];
            $keyCache = $this->redisService->createKeyCacheFromRequest( "ActivitieController_index" ,[null], $request, $namesRequest);
            
            $activities = Cache::remember($keyCache, $this->redisService->getRedisTtl(), function () use ($request, $startTime) {
                $query = $this->activitieService->getAllWithRelationsAndGeometry();
                $activitiesCollection = $this->activitieService->filter($request, $query)->get();

                return $this->activitieService->activitiesMap($request, $activitiesCollection, $startTime);
            });

            return ApiServices::statusCode200($activities);
        } catch (Exception $e) {
            return ApiServices::statusCode500($e->getMessage());
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreActivitieRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Activitie $activitie)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Activitie $activitie)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateActivitieRequest $request, Activitie $activitie)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Activitie $activitie)
    {
        //
    }
}
