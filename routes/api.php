<?php

use App\Http\Controllers\API\CicloController;
use App\Http\Controllers\API\CurriculoController;
use App\Http\Controllers\API\DocenteModuloController;
use App\Http\Controllers\API\FamiliaProfesionalController;
use App\Http\Controllers\API\MatriculaController;
use App\Http\Controllers\API\MenuOpcionController;
use App\Http\Controllers\API\ModuloController;
use App\Http\Controllers\API\RoleController;
use App\Http\Controllers\API\TokenController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Psr\Http\Message\ServerRequestInterface;
use Tqdev\PhpCrudApi\Api;
use Tqdev\PhpCrudApi\Config\Config;

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('v1')->group(function () {
    // Tokens (Sanctum)
    Route::post('tokens', [TokenController::class, 'store']);
    Route::delete('tokens', [TokenController::class, 'destroy'])->middleware('auth:sanctum');

    // Endpoints ya existentes en el proyecto
    Route::apiResource('ciclos', CicloController::class);

    Route::middleware(['auth:sanctum'])->apiResource('curriculos', CurriculoController::class);

    Route::apiResource('familias_profesionales', FamiliaProfesionalController::class)
        ->parameters([
            'familias_profesionales' => 'familiaProfesional',
        ]);

    // Endpoints exactos para frontend (auth por Sanctum o fallback dev en controlador)
    Route::get('roles', [RoleController::class, 'index']);
    Route::get('modulos/impartidos', [ModuloController::class, 'impartidos']);
    Route::get('modulos/matriculados', [ModuloController::class, 'matriculados']);
    Route::get('menu/administrador', [MenuOpcionController::class, 'administrador']);

    // CRUD REST
    Route::middleware(['auth:sanctum'])->group(function () {
        Route::apiResource('roles', RoleController::class)->except(['index']);
        Route::apiResource('modulos', ModuloController::class);
        Route::apiResource('matriculas', MatriculaController::class);
        Route::apiResource('docentes-modulos', DocenteModuloController::class)
            ->parameters(['docentes-modulos' => 'docenteModulo']);
        Route::apiResource('menu-opciones', MenuOpcionController::class)
            ->parameters(['menu-opciones' => 'menuOpcion']);
    });
});

// Rutas PHP-CRUD-API existentes
Route::any('/{any}', function (ServerRequestInterface $request) {
    $config = new Config([
        'address' => env('DB_HOST', '127.0.0.1'),
        'database' => env('DB_DATABASE', 'forge'),
        'username' => env('DB_USERNAME', 'forge'),
        'password' => env('DB_PASSWORD', ''),
        'basePath' => '/api',
    ]);
    $api = new Api($config);
    $response = $api->handle($request);

    try {
        $records = json_decode($response->getBody()->getContents())->records;
        $response = response()->json($records, 200, $headers = ['X-Total-Count' => count($records)]);
    } catch (\Throwable $th) {
    }

    return $response;
})->where('any', '.*')->middleware(['auth:sanctum']);
