<?php

namespace Modelos;

class ActiveRecords {
    //BASE DE DATOS

    protected static $db;
    protected static $columnasDB = [];
    protected static $tabla = '';

    //Errores y validaciones
    protected static $errores = [];

     //Definir la conexión a la BBDD
     public static function setDB($database) {
        self::$db = $database;
    }

    public function crear(){

        //Sanitizar los datos
        $atributos = $this -> sanitizarAtributos();

        $query = " INSERT INTO " .static::$tabla. " ( ";
        $query.= join(', ',array_keys($atributos));
        $query.= ") VALUES (' ";
        $query.= join("', '",array_values($atributos));
        $query.= " ') ";

        // Insertamos el query en la base de datos. El self es porque es static
        $resultado = self::$db->query($query);

        if($resultado) {
            // Redireccionar al usuario.
            header('location: /admin?resultado=1');
        }
    }

    public function actualizar(){

        $atributos = $this -> sanitizarAtributos();

        $valores = [];
        foreach ($atributos as $key => $value){
            $valores []= "{$key}='{$value}'";
        }
        
        $query = "UPDATE " . static::$tabla . " SET ";
        $query .= join(', ', $valores);
        $query .= " WHERE id = '". self::$db->escape_string($this->id)."'";
        $query .= " LIMIT 1";

        $resultado = self::$db->query($query);

        return $resultado;

    }

    public function eliminar(){
        $query = "DELETE FROM " . static::$tabla . " WHERE id = ". self::$db->escape_string($this->id)." LIMIT 1";
        $resultado=self::$db->query($query);

        if($resultado){
            $this->borrarImagen();
            header('location: /admin?resultado=3');
        }
    }

    //Este metodo identifica y unir las columnas de la base de datos
    public function atributos(){
        $atributos = [];
        foreach (static::$columnasDB as $columna){
            if ($columna == 'id') continue;
            $atributos[$columna] = $this -> $columna;
        }
        return $atributos;
    }

    public function sanitizarAtributos(){
        $atributos = $this->atributos();
        $sanitizado = [];
        foreach($atributos as $key => $value){
            $sanitizado [$key] = self::$db->escape_string($value);
        }

        return $sanitizado;
    }

    //Subida de archivos
    public function setImagen($imagen){
        // Elimina la imagen previa
        if (!is_null ($this->id)){
                $this->borrarImagen();
            } 

        if($imagen){
            $this->imagen = $imagen;
        }
    }

    //Elininar archivos

    public function borrarImagen(){
        //Comprobar si existe el archivo
        $existeArchivo = file_exists(CARPETAS_IMAGENES . $this->imagen);
        if($existeArchivo){
            unlink(CARPETAS_IMAGENES . $this->imagen);
        }
    }

    //Validación
    public static function getErrores(){
        return static::$errores;
    }

    public function validar (){
        static::$errores=[];
        return static::$errores;
    }

    //Lista todas las propiedades
    public static function all(){
        $query = "SELECT * FROM " . static::$tabla;

        $resultado = self::consultarSQL($query);

        return $resultado;
    }

    //Obtiene determinado número de registros
    public static function get($cantidad){
        $query = "SELECT * FROM " . static::$tabla . " LIMIT " . $cantidad;

        $resultado = self::consultarSQL($query);

        return $resultado;
    }

    //Busca un registro por su id
    public static function find($id){
        $query = "SELECT * FROM " . static::$tabla . " WHERE id ={$id}";

        $resultado = self::consultarSQL($query);

        return array_shift($resultado);
    }

    public static function consultarSQL($query){
        //Consultar la base de datos
        $resultado = self::$db->query($query);

        //Iterar los resultados
        $array = [];

        while ($registro = $resultado->fetch_assoc()){
            $array[]= static::crearObjeto($registro);

        }

        //Liberar la memoria
        $resultado->free();

        //Retornar los resultados
        return $array;

    }

    protected static function crearObjeto($registro){
        $objeto = new static;

        foreach($registro as $key=>$value){
            if(property_exists($objeto, $key)){
                $objeto->$key = $value;
            }
        }

        return $objeto;
    }

    //Sincroniza el objeto en memoria con los cambios realizados por el usuario
    public function sincronizar($args = []){
        foreach($args as $key=>$value){
            if(property_exists($this,$key) && !is_null($value)){
                $this->$key = $value;
            }
        }
    }
}