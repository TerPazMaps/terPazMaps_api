<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Icon;
use App\Models\Subclasse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Cache;
use App\Http\Requests\StoreSubclasseRequest;
use App\Http\Requests\UpdateSubclasseRequest;

class SubclasseController extends Controller
{
    private $redis_ttl;

    public function __construct()
    {
        $this->redis_ttl = 3600;
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $subclassesQuery = Icon::with('subclasse')->has('subclasse');

            $chaveCache = "SubclasseController_index";
            if ($request->name) {
                $chaveCache .= "_" . $request->name;
                $name = $request->name;

                // Adicionar a cláusula where para filtrar as subclasses pelo nome
                $subclassesQuery->whereHas('subclasse', function ($query) use ($name) {
                    $query->where('name', 'like', '%' . $name . '%');
                })->get();
            }

            $subclasses = Cache::remember($chaveCache, $this->redis_ttl, function () use ($subclassesQuery) {
                $subclasses = $subclassesQuery->get();

                $subclasses->transform(function ($item) {
                    $item['image_url'] = config('app.url') . "storage/" . substr($item->disk_name, 0, 3) . '/' . substr($item->disk_name, 3, 3) . '/' . substr($item->disk_name, 6, 3) . '/' . $item->disk_name;
                    return $item;
                });
                return $subclasses;
            });

            return response()->json($subclasses, 200);
        } catch (Exception $e) {
            return response()->json([
                "error" => [
                    "status" => "500",
                    "title" => "Internal Server Error",
                    "detail" => $e->getMessage(),
                ]
            ], 500);
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
