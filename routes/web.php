<!-- A estas alturas ya tendríamos que ser capaces de añadir contenido estático a nuestra web,
 simplemente modificando el fichero de rutas y devolviendo todo el contenido desde ese fichero.
 Para evitar tener que mantener un inmenso fichero routes/web.php con todo el código mezclado en
 el mismo archivo, en las siguientes secciones separaremos el código de las vistas y más adelante añadiremos los controladores.

En este ejercicio vamos a definir las rutas principales que va a tener nuestro sitio web.

Necesitaremos crear las vistas para poder realizar las operaciones CRUD sobre cada una de las tablas.
De momento, las vistas únicamente deben devolver el texto con la operación/visualización que deberán realizar en un futuro.

Las siguientes son las pantallas principales y un ejemplo del resultado del CRUD sobre la tabla

Método	Ruta	Texto a mostrar
GET	/	Pantalla principal
GET	login	Login usuario
GET	logout	Logout usuario
GET	proyectos	Listado proyectos
GET	proyectos/show/{id}	Vista detalle proyecto {id}
GET	proyectos/create	Añadir proyecto
GET	proyectos/edit/{id}	Modificar proyecto {id}
GET	perfil/{id}	Visualizar el currículo de {id}
Debemos asegurarnos de que todos los parámetros {id} sean números naturales.

El parámetro {id} es opcional. En el caso de que exista debe mostrar Visualizar el currículo de y el
número enviado, mientras que en caso de no enviar ningún valor para ese parámetro se debería mostrar Visualizar el currículo propio -->

<?php

use Illuminate\Routing\RouteGroup;
use Illuminate\Support\Facades\Route;

use function PHPUnit\Framework\isEmpty;

Route::get('/', function () { //GET	/	Pantalla principal
    return "Pantalla principal";
});

Route::get('/login', function () { //GET	login	Login usuario
    return "Login usuario";
});
Route::get('/logout', function () { //GET	login	Logout usuario
    return "Logout usuario";
});

Route::prefix('/proyectos')->group(function () {

    Route::get('/', function () { // GET	proyectos	Listado proyectos
        return "Listado proyectos";
    });


    Route::get('/show/{id}', function ($id) { //GET	proyectos/show/{id}	Vista detalle proyecto {id}
        return "Vista detalle proyecto $id";
    })->where('id', '[0-9]+');




    Route::get('/create', function () { //GET	proyectos/create	Añadir proyecto
        return "Añadir proyecto";
    });

    Route::get('/edit/{id}', function ($id) { //GET	proyectos/edit/{id}	Modificar proyecto {id}
        return "Modificar proyecto $id";
    })->where('id', '[0-9]+');
});


Route::get('/perfil/{id?}', function ($id) { //GET	perfil/{id}	Visualizar el currículo de {id}
    $mensaje="";
    if(isset($id) && !empty($id)){ // Si no es nulo o vacio id
       $mensaje= "Visualizar el currículo de $id"; // Envio mensaje 1
    } else {
        $mensaje = "Visualizar el currículo propio"; // si no mensaje 2
    }
    return $mensaje;

})->where('id', '[0-9]+');
