<?php

namespace Controladores;
use MVC\Router;
use Modelos\Propiedad;
use PHPMailer\PHPMailer\PHPMailer;

class PaginasControlador {
    public static function index (Router $router){
        
        $propiedades = Propiedad::get(3);
        $inicio = true;

        $router -> render('paginas/index', [
            'propiedades' => $propiedades,
            'inicio' => $inicio
        ]);
    }

    public static function nosotros (Router $router){
        $router -> render('paginas/nosotros');
    }

    public static function propiedades (Router $router){

        $propiedades=Propiedad::all();
        
        $router -> render('paginas/propiedades', [
            'propiedades' => $propiedades
        ]);
    }

    public static function propiedad (Router $router){

        $id = validarORedireccionar('/propiedades');

        //Buscar la propiedad por su ID
        $propiedad=Propiedad::find($id);
        
        $router -> render ('paginas/propiedad', [
            'propiedad' => $propiedad
        ]);
    }

    public static function blog (Router $router){
        
        $router -> render('paginas/blog');
    }

    public static function entrada (Router $router){
        
        $router -> render('paginas/entrada');
    }

    public static function contacto (Router $router){

        $mensaje = null;

        if($_SERVER['REQUEST_METHOD'] === 'POST'){

            $respuestas = $_POST['contacto'];

            //Crear una instancia de php mailer
            $mail = new PHPMailer();

            //Configurar SMTP
            $mail -> isSMTP();
            $mail -> Host = 'sandbox.smtp.mailtrap.io';
            $mail -> SMTPAuth = true;
            $mail -> Username = 'a0d54eeee73624';
            $mail -> Password = 'b00efc403d6f2d';
            $mail -> SMTPSecure = 'tls';
            $mail -> Port = 2525;

            //Configurar el contenido del email
            $mail -> setFrom('admin@bienesraices.com');
            $mail -> addAddress('admin@bienesraices.com', 'BienesRaices.com');
            $mail -> Subject = 'Tienes un nuevo mensaje';

            //Habilitat HTML
            $mail -> isHTML(true);
            $mail -> CharSet = 'UTF-8';


            //Definir el contenido
            $contenido = '<html>';
            $contenido .= '<p> Tienes un nuevo mensaje </p>';
            $contenido .= '<p> Nombre: ' . $respuestas['nombre'] . '</p>';
            $contenido .= '<p> Mensaje: ' . $respuestas['mensaje'] . '</p>';
            $contenido .= '<p> Tipo de transacción: ' . $respuestas['tipo'] . '</p>';
            $contenido .= '<p> Precio o presupuesto: ' . $respuestas['precio'] . ' € </p>';
            $contenido .= '<p> Preferencia de contacto: ' . $respuestas['contacto'] . '</p>';

            //Enviar de forma condicional algunos campos de email o telefonos

            if ($respuestas['contacto'] === 'telefono'){
                $contenido .= '<p> Telefono: ' . $respuestas['telefono'] . '</p>';
                $contenido .= '<p> Fecha de contacto: ' . $respuestas['fecha'] . '</p>';
                $contenido .= '<p> Hora de contacto: ' . $respuestas['hora'] . '</p>';

            }else {
                $contenido .= '<p> Email: ' . $respuestas['email'] . '</p>';
            }


            $contenido .= '</html>';

            $mail -> Body = $contenido;
            $mail ->AltBody = "Esto es texto alternativo sin HTML";

            //Enviar el email
            if($mail -> send()){
                $mensaje = "Mensaje enviado correctamente";
            } else {
                $mensaje = "El mensaje no se pudo enviar...";
            }

        }
        
        $router -> render('paginas/contacto',[
            'mensaje' => $mensaje
        ]);
    }
}