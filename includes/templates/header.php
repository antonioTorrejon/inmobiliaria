<?php

if(!isset($_SESSION)){
    session_start();
}

$auth = $_SESSION['login'] ?? false;

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bienes raices</title>
    <link rel="stylesheet" href="/bienesraices/build/css/app.css"
</head>

<body>

    <header class="header <?php echo $inicio ? 'inicio' : ''; ?>">
        <div class="contenedor contenido-header">
            <div class="barra">
                <a href="/">
                    <img class="logo" src="../../../bienesraices/build/img/logo.svg" alt="logotipo bienes raices">
                </a>

                <div class="mobile-menu">
                    <img src="../../../bienesraices/build/img/barras.svg" alt="icono menu responsive">
                </div>

                <div class="derecha">
                    <img class="dark-mode-boton" src="../../../bienesraices/build/img/dark-mode.svg">              
                    <nav class="navegacion">
                        <a href="nosotros.php">Nosotros</a>
                        <a href="anuncios.php">Anuncios</a>
                        <a href="blog.php">Blog</a>
                        <a href="contacto.php">Contacto</a>
                        <?php if($auth): ?>
                            <a href="cerrar-sesion.php">Cerrar sesión</a>
                        <?php endif ?>
                    </nav>
                </div>
                
            </div>
            <?php 
                if($inicio){
                    echo "<h1>Venta de casas y departamentos exclusivos de lujo</h1>";
                }
            ?>

        </div>
    </header>