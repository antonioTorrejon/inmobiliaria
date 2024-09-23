<?php

namespace Modelos;

class Admin extends ActiveRecords {
    //Base de datos
    protected static $tabla = 'usuario';
    protected static $columnasDB = ['id', 'email', 'password'];

    public $id;
    public $email;
    public $password;

    public function __construct($args = [])
    {
        $this->id = $args['id'] ?? null;
        $this->email = $args['email'] ?? '';
        $this->password = $args['password'] ?? '';
    }

    public function validar(){
        if (!$this->email){
            self::$errores[] = 'El email es obligatorio';
        }
        if (!$this->password){
            self::$errores[] = 'El password es obligatorio';
        }

        return self::$errores;
    }

    public function existeUsuario(){
        //Revisar si un usuario existe o no
        $query = "SELECT * FROM " . self::$tabla . " WHERE email = '" . $this->email . "' LIMIT 1";

        $resultado = self::$db -> query ($query);

        if (!$resultado->num_rows){
            self::$errores [] ="El usuario introducido no existe";
            return;
        }
        return $resultado;
    }

    public function comprobarPassword($resultado){
        $usuario = $resultado -> fetch_object();

        // $autentificado = password_verify($this->password, $usuario->password);

        if(!$usuario){
            self::$errores[] = "El password introducido es incorrecto";
        }

        return $usuario;

    }

    public function autentificar(){
        session_start();

        //llenar el arreglo de la sesión
        $_SESSION['usuario'] = $this->email;
        $_SESSION['login'] =true;

        header('Location: /admin');

    }

}

