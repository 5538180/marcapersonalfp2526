<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserIdiomaRequest;
use App\Http\Requests\UpdateUserIdiomaRequest;
use App\Http\Resources\IdiomaResource;
use App\Http\Resources\UserIdiomaResource;
use App\Models\Idioma;
use App\Models\User;
use App\Models\UserIdioma;

use Illuminate\Http\Request;

class UserIdiomaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, User $user)
    {
        return $user->idiomas()/* ->orderBy("english_name", "asc") */->get();
    }
    /**
     * Show the form for creating a new resource.
     */


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, User $user)
    {

        $userData = json_decode($request->getContent(), true); // Datos de peticion decodificando el json para interpretar php y guardado en variable
        $user->idiomas()->attach($userData); // Al user (user_id) se le aplica el metodo idiomas y con el attach (metodo tabla intermedia /pivot)  es para relacionar los datos de la peticion con el user_id y el idioma
        return response()->json($user, 201); //Devolvemos los datos en json y damos repuesto 201
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user, Idioma $idioma) // Muestra los idiomas de un usuario por id
    {
        $userIdioma = $user->idiomas()->findOrFail($idioma); // En una variable almacenamos el idioma asociado a un usuario por id (user_id, idioma_id)
        return new UserIdiomaResource($userIdioma); // Devolucion de una instancia del recurso con el usuario y el idioma de la variable anterior
    }



    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user, Idioma $idioma) // Actualizamos el idioma relacionado con el user_id
    {
        $userData = json_decode($request->getContent(), true); // Datos de peticion decodificando el json para interpretar php y guardado en variable
        $idiomaUser =  $user->idiomas()->findOrFail($idioma); // ALmacenamos el idioma del usuario si lo encuentra
        $user->idiomas()->updateExistingPivot($idioma,$userData->all()); // Actualizamos la tabla intermedia / pivos (UserIdioma) con el nuevo idioma asocioado al usuario
        return new UserIdiomaResource($idiomaUser); // Devolucion de una instancia del recurso con el usuario y el idioma actualizado de la variable anterior

    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy( User $user , Idioma $idioma)
    {
        try {
             $user->idiomas()->detach($idioma->id); // destuimos la relacion entre el usuario y su idioma asociado por el metodo idiomas() con el metodo detach y seleccionando el id del idioma por el parametro de entrada idioma

            return response()->json(null, 204);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error: ' . $e->getMessage()
            ], 400);
        }
    }
}
