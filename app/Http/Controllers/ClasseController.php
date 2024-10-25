<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Classe;
use App\Services\ApiServices;
use App\Services\RedisService;
use App\Services\ClasseService;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Cache;
use App\Http\Requests\StoreClasseRequest;
use App\Http\Requests\UpdateClasseRequest;

class ClasseController extends Controller
{
    private $redis_ttl;
    protected $redisService;
    protected $classeService;

    public function __construct()
    {
        $this->redis_ttl = 3600;
        $this->redisService = new RedisService();
        $this->classeService = new ClasseService();
    }

    /**
     * Display a listing of the resource.
     */
    // http://127.0.0.1:8000/api/v5/geojson/classe/
    public function index()
    {
        try {
            $classes = Cache::remember("ClasseController_index", $this->redisService->getRedisTtlLow(), function () {
                return $this->classeService->index();
            });

            return response()->json($classes);
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
    public function store(StoreClasseRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id)
    {
        try {
            $classe = Cache::remember("ClasseController_show_" . $id, $this->redis_ttl, function () use ($id) {
                return $this->classeService->show($id);
            });

            if (!$classe['geojson']) {
                return ApiServices::statuscode404("Classe não encontrada no banco de dados.");
            }

            return ApiServices::statuscode200($classe);
        } catch (Exception $e) {
            return ApiServices::statuscode500($e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function getSubclassesByClass(int $id)
    {
        try {
            $classes = Cache::remember("ClasseController_getSubclassesByClass_" . $id, $this->redisService->getRedisTtlLow(), function () use ($id) {
                return $this->classeService->getSubclassesByClass($id);
            });
            $classes = $this->classeService->getSubclassesByClass($id);
            
            return response()->json($classes);
        } catch (Exception $e) {
            return ApiServices::statuscode500($e->getMessage());
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Classe $classe)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateClasseRequest $request, Classe $classe)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Classe $classe)
    {
        //
    }
}
