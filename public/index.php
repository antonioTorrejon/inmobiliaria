<?php

require_once __DIR__ . '/../includes/app.php';

use MVC\Router;
use Controladores\PropiedadControlador;
use Controladores\VendedorControlador;
use Controladores\PaginasControlador;
use Controladores\LoginControlador;

$router = new Router();

//Zona privada
$router->get ('/admin', [PropiedadControlador::class, 'index']);
$router->get ('/propiedades/crear', [PropiedadControlador::class, 'crear']);
$router->post ('/propiedades/crear', [PropiedadControlador::class, 'crear']);
$router->get ('/propiedades/actualizar', [PropiedadControlador::class, 'actualizar']);
$router->post ('/propiedades/actualizar', [PropiedadControlador::class, 'actualizar']);
$router->post ('/propiedades/eliminar', [PropiedadControlador::class, 'eliminar']);

$router->get ('/vendedores/crear', [VendedorControlador::class, 'crear']);
$router->post ('/vendedores/crear', [VendedorControlador::class, 'crear']);
$router->get ('/vendedores/actualizar', [VendedorControlador::class, 'actualizar']);
$router->post ('/vendedores/actualizar', [VendedorControlador::class, 'actualizar']);
$router->post ('/vendedores/eliminar', [VendedorControlador::class, 'eliminar']);

$router->get('/admin?resultado=1',[PropiedadController::class,'index']);
$router->get('/admin?resultado=2',[PropiedadController::class,'index']);
$router->get('/admin?resultado=3',[PropiedadController::class,'index']);

//Zona pública
$router->get ('/', [PaginasControlador::class, 'index']);
$router->get ('/nosotros', [PaginasControlador::class, 'nosotros']);
$router->get ('/propiedades', [PaginasControlador::class, 'propiedades']);
$router->get ('/propiedad', [PaginasControlador::class, 'propiedad']);
$router->get ('/blog', [PaginasControlador::class, 'blog']);
$router->get ('/entrada', [PaginasControlador::class, 'entrada']);
$router->get ('/contacto', [PaginasControlador::class, 'contacto']);
$router->post ('/contacto', [PaginasControlador::class, 'contacto']);

//Login y autentificación
$router->get('/login', [LoginControlador::class, 'login']);
$router->post('/login', [LoginControlador::class, 'login']);
$router->get('/logout', [LoginControlador::class, 'logout']);

$router->comprobarRutas();

