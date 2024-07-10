<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Icon;
use App\Models\Subclasse;
use Illuminate\Http\Request;
use App\Services\ApiServices;
use App\Services\RedisService;
use App\Services\SubclasseService;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Cache;
use App\Http\Requests\StoreSubclasseRequest;
use App\Http\Requests\UpdateSubclasseRequest;

class SubclasseController extends Controller
{
    private $redis_ttl;
    protected $redisService;
    protected $subclasseService;

    public function __construct()
    {
        $this->redis_ttl = 3600;
        $this->redisService = new RedisService();
        $this->subclasseService = new SubclasseService();
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $keyCache = $this->redisService->createKeyCacheFromRequest($request, "SubclasseController_index", ["name"]);
            $subclassesQuery = $this->subclasseService->index($request);

            $subclasses = Cache::remember($keyCache, $this->redis_ttl, function () use ($subclassesQuery) {
                return $this->subclasseService->transform($subclassesQuery);
            });

            if ($subclasses['geojson']->isEmpty()) {
                if ($request->name) {
                    return ApiServices::statuscode404("Nenhuma subclasse com o nome: ".$request->name);
                }
                return ApiServices::statuscode404("Sem registros de subclasse ");
            }

            return ApiServices::statusCode200($subclasses);
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
    public function store(StoreSubclasseRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Subclasse $subclasse)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Subclasse $subclasse)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSubclasseRequest $request, Subclasse $subclasse)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Subclasse $subclasse)
    {
        //
    }
}
