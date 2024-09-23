<?php

namespace MVC;

class Router {

    public $rutasGET = [];
    public $rutasPOST = [];

    public function get($url, $fn){
        $this->rutasGET[$url] = $fn;
    }

    public function post($url, $fn){
        $this->rutasPOST[$url] = $fn;
    }

    public function comprobarRutas(){

        session_start();

        $auth = $_SESSION['login'] ?? null;

        //Arreglo de rutas protegidas
        $rutas_protegidas = ['/admin', '/propiedades/crear', '/propiedades/actualizar', '/propiedades/eliminar', '/vendedores/crear', '/vendedores/actualizar', '/vendedores/eliminar', ];

        //Leemos la URL actual
        $urlActual = $_SERVER['PATH_INFO'] ?? '/';
        
        //Leemos el metodo: GET o POST
        $metodo = $_SERVER['REQUEST_METHOD'];

        if ($metodo === 'GET' ){
            $fn = $this->rutasGET[$urlActual] ?? null;
        } else {
            $fn = $this->rutasPOST[$urlActual] ?? null;
        }

        //Proteger las rutas
        if(in_array($urlActual, $rutas_protegidas) && !$auth){
            header('Location: /');
        }

        if ($fn){
            //Significa que exite la URL y tiene una función asociada
            call_user_func($fn, $this);
        }else {
            echo "Pagina inexistente o no encontrada";
        }
    }

    //Función para mostrar una vista
    public function render($vista, $datos = []){

        foreach ($datos as $key => $value){
            $$key = $value;
        }

        ob_start(); //Almacenamiento en memoria durante un momento

        include __DIR__ . "/vistas/$vista.php";
        $contenido = ob_get_clean(); //Limpia el buffer
        include __DIR__ . "/vistas/layout.php";
    }

}