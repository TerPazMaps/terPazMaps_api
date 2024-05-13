<?php

namespace App\Http\Controllers;

use App\Models\Street;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\StoreStreetRequest;
use App\Http\Requests\UpdateStreetRequest;

class StreetController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Dados da tabela streets

        $data = Street::select(
            'id',
            'region_id',
            'street_condition_id',
            DB::raw('ST_AsText(geometry) as geometry'),
            'properties',
            'color',
            'width',
            'continuous',
            'line_cap',
            'line_dash_pattern',
            'created_at',
            'updated_at'
        )->get();
        
        // Montando o comando INSERT
        $insertQuery = "INSERT INTO streets (id, region_id, street_condition_id, geometry, properties, color, width, continuous, line_cap, line_dash_pattern, created_at, updated_at) VALUES ";
        
        foreach ($data as $dt) {
            $insertQuery .= "(" .
                $dt->id . ", " .
                $dt->region_id . ", " .
                $dt->street_condition_id . ", " .
                "'" . pg_escape_string($dt->geometry) . "', " .
                "'" . pg_escape_string($dt->properties) . "', " .
                "'" . pg_escape_string($dt->color) . "', " .
                $dt->width . ", " .
                ($dt->continuous ? 'TRUE' : 'FALSE') . ", " .
                "'" . pg_escape_string($dt->line_cap) . "', " .
                "'" . pg_escape_string($dt->line_dash_pattern) . "', " .
                "'" . $dt->created_at . "', " .
                "'" . $dt->updated_at . "'),";
        }
        
        // Removendo a vírgula extra no final e fechando a instrução SQL completa
        $insertQuery = rtrim($insertQuery, ", ") . ";";
        
        return response()->json([
            "success" => [
                "status" => "200",
                "title" => "OK",
                "detail" => ["insertSQL" => $insertQuery],
            ]
        ], 200);
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
    public function store(StoreStreetRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Street $street)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Street $street)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateStreetRequest $request, Street $street)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Street $street)
    {
        //
    }
}
