<?php

function conectarDB() : mysqli{
    $db = new mysqli('localhost', 'root', 'tym080598', 'bienesraices_crud');

    if(!$db){
        echo "Error. No se pudo conectar";
        exit;
    }

    return $db;
}