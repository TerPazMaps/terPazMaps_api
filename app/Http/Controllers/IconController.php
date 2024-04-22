<?php

namespace App\Http\Controllers;

use App\Models\Icon;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Cache;
use App\Http\Requests\StoreIconRequest;
use App\Http\Requests\UpdateIconRequest;

class IconController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function index()
    {
        $chaveCache = "IconController_index";
        $icons = Cache::remember($chaveCache, 3600, function () {
            return Icon::with('subclasse')
            ->has('subclasse') // Somente ícones que têm uma atividade relacionada com uma subclass correspondente
            ->get();
        });

        return json_encode($icons);
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
