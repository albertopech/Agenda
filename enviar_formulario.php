<?php
// Incluir los archivos necesarios de PHPMailer
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';
require 'PHPMailer/src/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

function enviarCorreoDesdeFormulario() {
    // Comprobar si se envió el formulario
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Recoger datos del formulario
        $email = $_POST['email'];
        $nombre = $_POST['name'];
        $telefono = $_POST['phone'];
        $mensaje = $_POST['message'];

        try {
            // Instanciar el objeto PHPMailer
            $mail = new PHPMailer(true);
            $mail->CharSet = 'UTF-8';

            // Configurar el servidor SMTP
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'angelrayaviles20@gmail.com'; // Correo electrónico desde el que enviarás los correos
            $mail->Password = 'bjkk dupq lpnq ncxe'; // Contraseña de tu correo electrónico
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;

            // Configurar correo electrónico
            $mail->setFrom('angelrayaviles20@gmail.com', 'Formulario Agenda Web'); // Cambia aquí por tu correo electrónico y tu nombre
            $mail->addAddress('angelrayaviles20@gmail.com'); // Cambia aquí por la dirección de correo a la que quieres enviar
            $mail->Subject = 'Nuevo mensaje del formulario de contacto';
            $mail->Body = "Correo electrónico: $email <br>" .
                          "Nombre: $nombre <br>" .
                          "Teléfono: $telefono <br>" .
                          "Mensaje: $mensaje";
            $mail->isHTML(true);

            // Enviar correo
            $mail->send();
        } catch (Exception $e) {
            
        }
    }
}

// Llamar a la función para enviar el correo si se envió el formulario
enviarCorreoDesdeFormulario();
?>
