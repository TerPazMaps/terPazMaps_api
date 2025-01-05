<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Icon;
use App\Services\IconService;
use App\Services\ApiServices;
use App\Services\RedisService;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Cache;
use App\Http\Requests\StoreIconRequest;
use App\Http\Requests\UpdateIconRequest;

class IconController extends Controller
{
    protected $IconService;
    protected $redisService;

    public function __construct()
    {
        $this->IconService = new IconService();
        $this->redisService = new RedisService();
    }

    /**
     * Display a listing of the resource.
     */

    public function index()
    {
        try {   
            $chaveCache = "IconController_index";
            $icons = Cache::remember($chaveCache, $this->redisService->getRedisTtl(), function () {
                return $this->IconService->index();
            });

            return ApiServices::statuscode200(["geojson" => $icons]);
        } catch (Exception $e) {
            return ApiServices::statuscode500($e->getMessage());
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
    public function store(StoreIconRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Icon $icon)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Icon $icon)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateIconRequest $request, Icon $icon)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Icon $icon)
    {
        //
    }
}
