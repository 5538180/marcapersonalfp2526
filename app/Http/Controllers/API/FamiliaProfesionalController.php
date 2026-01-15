<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\CicloResource;
use App\Http\Resources\FamiliaProfesionalResource;
use App\Models\FamiliaProfesional;
use Illuminate\Http\Request;

class FamiliaProfesionalController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return FamiliaProfesionalResource::collection(FamiliaProfesional::all());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(FamiliaProfesional $familiaProfesional)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, FamiliaProfesional $familiaProfesional)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(FamiliaProfesional $familiaProfesional)
    {
        //
    }
}
