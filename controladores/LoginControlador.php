<?php

namespace Controladores;
use MVC\Router;
use Modelos\Admin;


class LoginControlador {
    public static function login(Router $router){

        $errores = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST'){

            $auth = new Admin($_POST);

            $errores = $auth -> validar();
           
            if(empty($errores)){
                //Verificar si el usuario existe
                $resultado = $auth -> existeUsuario();

                if(!$resultado){
                    $errores = Admin::getErrores();
                } else {
                    //Verificar el password
                    $autentificado = $auth -> comprobarPassword($resultado);

                    if($autentificado){
                        //Autentificar el usuario
                        $auth->autentificar();
                    } else {
                        //Password incorrecto (mensaje)
                        $errores = Admin::getErrores();
                    }
                }
            }
        }

        
        
        $router -> render('auth/login', [
            'errores'=> $errores
        ]);
    
    }

    public static function logout(){
        session_start();

        $_SESSION = [];

        header('Location: /');
    }

}