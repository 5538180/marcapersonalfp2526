<?php

use App\Http\Controllers\API\CicloController;
use App\Http\Controllers\API\FamiliaProfesionalController;
use App\Http\Controllers\API\IdiomaController;
use App\Http\Controllers\API\UserIdiomaController;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Psr\Http\Message\ServerRequestInterface;
use Tqdev\PhpCrudApi\Api;
use Tqdev\PhpCrudApi\Config\Config;

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {

    return $request->user()/*  . "<br><br>" . $request->user()->curriculo */;
});

// Rutas /api/v1

Route::prefix('v1')->group(function () {
    Route::apiResource('ciclos', CicloController::class);

    Route::apiResource('familias_profesionales', FamiliaProfesionalController::class)
        ->parameters([
            'familias_profesionales' => 'familiaProfesional'
        ]);

    Route::apiResource('idiomas', IdiomaController::class);

    //api/vi/user/{user_id}/idioma
    Route::apiResource('users.idiomas', UserIdiomaController::class)
    ->parameters([
        'users'=>'user',
        'idioma_id'=>'idioma'
    ])
    ;
});



// Rutas PHP-CRUD-API
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
