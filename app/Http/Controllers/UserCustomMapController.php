<?php

namespace App\Http\Controllers;

use Exception;
use Carbon\Carbon;
use App\Models\UserCustomMap;
use App\Services\ApiServices;
use App\Services\RedisService;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Services\UserCustomMapService;
use App\Http\Requests\StoreUserCustomMapRequest;
use App\Http\Requests\UpdateUserCustomMapRequest;

class UserCustomMapController extends Controller
{
    private $redis_ttl;
    protected $redisService;
    protected $userCustomMapService;

    public function __construct()
    {
        $this->redis_ttl = 3600;
        $this->redisService = new RedisService();
        $this->userCustomMapService = new UserCustomMapService();
    }

    /**
     * Display a listing of the resource.
     */
    // http://127.0.0.1:8000/api/v5/geojson/user-custom-maps
    public function index()
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();
            $mapas = $this->userCustomMapService->index($user->id);

            if ($mapas->isEmpty()) {
                return ApiServices::statusCode404("Este usuário não possui registros");
            }
            $mapasTransformados =  $this->userCustomMapService->index_map($mapas);

            return ApiServices::statusCode200(["geojson" => $mapasTransformados]);
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
    public function store(StoreUserCustomMapRequest $request)
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();
            $request->validated();
            
            $userCustomMap = new UserCustomMap();
            $userCustomMap->user_id = $user->id;
            $userCustomMap->name = $request->name;
            
            $geometryAndCenter = $this->userCustomMapService->constructGeometryAndCenter($request);

            $userCustomMap->geometry = $geometryAndCenter[0];
            $userCustomMap->center = $geometryAndCenter[1];

            // Salvar o modelo no banco de dados
            if ($userCustomMap->save()) {
                return ApiServices::statusCode201("Salvo com sucesso");
            } else {
                return ApiServices::statusCode500("Erro ao salvar");
            }
        } catch (Exception $e) {
            return ApiServices::statuscode500($e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();
            $mapa = $this->userCustomMapService->show($id);

            if (!$mapa) {
                return ApiServices::statusCode404("Registo do mapa personalizado não encontrado");
            }

            if ($user->id != $mapa->user_id) {
                return ApiServices::statusCode403("Usuário não tem permissão para acessar o registro");
            }

            $geojson_mapa = $this->userCustomMapService->show_map($mapa);
              
            return ApiServices::statusCode200($geojson_mapa);
        } catch (Exception $e) {
            return ApiServices::statuscode500($e->getMessage());
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(UserCustomMap $user_custom_maps)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserCustomMapRequest $request, $id)
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();
            $mapa = UserCustomMap::find($id);
            if (!$mapa) {
                return ApiServices::statusCode404("Registo do mapa personalizado não encontrado");
            }

            if ($user->id != $mapa->user_id) {
                return ApiServices::statusCode403("Usuário não tem permissão para acessar o registro");
            }
            $validatedData = $request->validated();

            $geometryAndCenter = $this->userCustomMapService->constructGeometryAndCenter($request);

            $validatedData['geometry'] = $geometryAndCenter[0];
            $validatedData['center'] = $geometryAndCenter[1];

            $mapa->fill($validatedData);

            // Salve as alterações no banco de dados
            if ($mapa->save()) {
                return ApiServices::statusCode200("Atualizado com sucesso");
            } else {
                return ApiServices::statusCode500("Erro ao atualizar");
            }
        } catch (Exception $e) {
            return ApiServices::statuscode500($e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();
            $mapa = UserCustomMap::find($id);

            if (!$mapa) {
                return ApiServices::statusCode404("Registro não encontrado");
            }

            if ($user->id != $mapa->user_id) {
                return ApiServices::statusCode403("Usuário não tem permissão para acessar o registro");
            }

            if ($mapa->delete()) {
                return ApiServices::statusCode200("Deletado com sucesso");
            } else {
                return ApiServices::statusCode500("Erro ao deletar");
            }
        } catch (Exception $e) {
            return ApiServices::statuscode500($e->getMessage());
        }
    }
}
