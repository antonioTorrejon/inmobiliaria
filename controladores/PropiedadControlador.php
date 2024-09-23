<?php

namespace Controladores;
use MVC\Router;
use Modelos\Propiedad;
use Modelos\Vendedor;
use Intervention\Image\ImageManagerStatic as Image;

class PropiedadControlador {
    public static function index(Router $router){

        //Consulta a la base de datos con los métodos definidos en ActiveRecords
        $propiedades = Propiedad::all();
        $vendedores = Vendedor::all();
        $resultado = $_GET['resultado'] ?? null;

        $router->render('propiedades/admin', [
            'propiedades' => $propiedades,
            'resultado' => $resultado,
            'vendedores' => $vendedores
        ]);

    }

    public static function crear(Router $router){

        $propiedad = new Propiedad;
        $vendedores = Vendedor::all();
        $errores = Propiedad::getErrores();

        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            /** Creamos una nueva instancia de la clase propiedad */
        $propiedad = new Propiedad($_POST['propiedad']);

        //Generar un nombre único
        $nombreImagen = md5(uniqid(rand(), true)).".jpg";

        //Seteamos la imagen
        //Realiza un resize a la imagen con intervention
        if($_FILES['propiedad']['tmp_name']['imagen']){
            $image = Image::make($_FILES['propiedad']['tmp_name']['imagen'])->fit(800,600);
            $propiedad->setImagen($nombreImagen);
        }

        //Validamos
        $errores = $propiedad -> validar();

        if(empty($errores)){

            if(!is_dir(CARPETAS_IMAGENES)){
                mkdir(CARPETAS_IMAGENES);
            }
            
            //Guarda la imagen en el servidor
            $image->save(CARPETAS_IMAGENES . $nombreImagen);

            //Guarda en la base de datos
            $propiedad -> crear();

        }
        }
        
        $router ->render('propiedades/crear', [
            'propiedad' => $propiedad,
            'vendedores' => $vendedores,
            'errores' => $errores
        ]);
    }

    public static function actualizar(Router $router){
        $id = validarORedireccionar('/admin');

        $propiedad = Propiedad::find($id);
        $errores = Propiedad::getErrores();
        $vendedores = Vendedor::all();

        if ( $_SERVER['REQUEST_METHOD'] == 'POST'){

            //Asignar los atributos
            $args=$_POST['propiedad'];
    
            $propiedad->sincronizar($args);
    
            //Validación
            $errores = $propiedad->validar();
    
            //Subida de archivos
            //Generar un nombre único
            $nombreImagen = md5(uniqid(rand(), true)).".jpg";
    
            if($_FILES['propiedad']['tmp_name']['imagen']){
                $image = Image::make($_FILES['propiedad']['tmp_name']['imagen'])->fit(800,600);
                $propiedad->setImagen($nombreImagen);
            }
    
            if(empty($errores)){
                // Almacenar la imagen
                if($_FILES['propiedad']['tmp_name']['imagen']) {
                    $image->save(CARPETAS_IMAGENES . $nombreImagen);
                }
                $resultado = $propiedad->actualizar();
    
                if($resultado){
                    //Redireccionar al usuario
                    header('location: /admin?resultado=2');
                }
            }
        }    

        $router -> render('/propiedades/actualizar', [
            'propiedad' => $propiedad,
            'errores' => $errores,
            'vendedores' => $vendedores
        ]);
    }

    public static function eliminar(){
        if($_SERVER['REQUEST_METHOD'] == 'POST'){

            $id = $_POST['id'];
            $id = filter_var($id, FILTER_VALIDATE_INT);
    
            if($id){
    
                $tipo = $_POST['tipo'];
    
                if (validarTipoContenido($tipo)){
                    $propiedad = Propiedad::find($id);
                    $propiedad -> eliminar();
                }
            }
        }
    }
}