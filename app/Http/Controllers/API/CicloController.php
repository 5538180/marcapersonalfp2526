<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\CicloResource;
use App\Models\Ciclo;
use Illuminate\Http\Request;

class CicloController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {

        $query = Ciclo::query();
        if ($query) {
            $query->orWhere("nombre", "like", "%" . $request->q . "%");
        }
        return CicloResource::collection(
            $query->orderBy($request->sort ?? "id", $request->order ?? "asc")->paginate($request->per_page)
        );

        /* return CicloResource::collection(Ciclo::all()); */
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $ciclo = json_decode($request->getContent(), true);
        $ciclo = Ciclo::create($ciclo);
        return new CicloResource($ciclo);
    }

    /**
     * Display the specified resource.
     */
    public function show(Ciclo $ciclo)
    {
        return new CicloResource($ciclo);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Ciclo $ciclo)
    {
        $cicloData = json_decode($request->getContent(), true);
        $ciclo->update($cicloData);
        return new CicloResource($ciclo);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Ciclo $ciclo)
    {
        try {
           $ciclo->delete();
           return response()->json(null,204);
        } catch (\Exception $e) {
            return response()->json(["message"=>"Error: " . $e->getMessage()],400);
        }
    }
}
