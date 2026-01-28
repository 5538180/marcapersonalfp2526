<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserIdiomaRequest;
use App\Http\Requests\UpdateUserIdiomaRequest;
use App\Models\UserIdioma;
use Symfony\Component\HttpFoundation\Request;

class UserIdiomaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        /*  return UserIdioma::all(); */
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
    public function store(Request $request)
    {
       /*  $useridioma = json_decode($request->getContent(), true);

        $idioma = Idioma::create($idioma);

        return new IdiomaResource($idioma); */
    }

    /**
     * Display the specified resource.
     */
    public function show(UserIdioma $userIdioma)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(UserIdioma $userIdioma)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, UserIdioma $userIdioma)
    {
        /*   $cicloData = json_decode($request->getContent(), true);
        $ciclo->update($cicloData);

        return new CicloResource($ciclo); */
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(UserIdioma $userIdioma)
    {
        try {
            $userIdioma->delete();
            return response()->json(null, 204);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error: ' . $e->getMessage()
            ], 400);
        }
    }
}
