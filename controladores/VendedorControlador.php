<?php

namespace Controladores;
use MVC\Router;
use Modelos\Propiedad;
use Modelos\Vendedor;

class VendedorControlador {
    public static function crear(Router $router){
        $vendedores = new Vendedor;
        $errores = Propiedad::getErrores();

        if ( $_SERVER['REQUEST_METHOD'] == 'POST'){

            //Crear una nueva instancia
            $vendedor = new Vendedor($_POST['vendedor']);
        
            //Validar que no haya campos vacios
            $errores = $vendedor->validar();
        
            //No hay errores
            if(empty($errores)){
                $vendedor->crear();
            }
        }

        $router ->render('vendedores/crear', [
            'vendedores' => $vendedores,
            'errores' => $errores
        ]);
    }

    public static function actualizar(Router $router){
        
        $id = validarORedireccionar('/admin');

        $vendedor = Vendedor::find($id);
        $errores = Vendedor::getErrores();

        if ($_SERVER['REQUEST_METHOD'] == 'POST'){

            //Asignar los valores
           $args = $_POST['vendedor'];
        
           //Sincronizar objeto en memoria con lo que el usuario escribió
           $vendedor->sincronizar($args);
        
           //Validación 
           $errores = $vendedor->validar();
        
           if(empty($errores)){
        
            $resultado = $vendedor->actualizar();
        
                    if($resultado){
                        //Redireccionar al usuario
                        header('location: /admin?resultado=2');
                    }
           }
        
        }

        $router -> render('/vendedores/actualizar', [
            'errores' => $errores,
            'vendedor' => $vendedor
        ]);
    } 

    public static function eliminar(){
        if($_SERVER['REQUEST_METHOD'] == 'POST'){

            $id = $_POST['id'];
            $id = filter_var($id, FILTER_VALIDATE_INT);
    
            if($id){
    
                $tipo = $_POST['tipo'];
    
                if (validarTipoContenido($tipo)){
                    $propiedad = Vendedor::find($id);
                    $propiedad -> eliminar();
                }
            }
        }
    }

}