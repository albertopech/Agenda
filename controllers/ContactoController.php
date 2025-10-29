<?php
require_once '../models/Conexion.php';
require_once '../PHPMailer/src/PHPMailer.php';
require_once '../PHPMailer/src/SMTP.php';
require_once '../PHPMailer/src/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class ContactoController {
    private $conn;
    
    public function __construct() {
        $this->conn = Database::getInstance()->getConnection();
    }
    
    // ============ ENVIAR FORMULARIO DE CONTACTO ============
    public function enviarFormulario() {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $email = $_POST['email'] ?? '';
            $nombre = $_POST['name'] ?? '';
            $telefono = $_POST['phone'] ?? '';
            $mensaje = $_POST['message'] ?? '';
            
            try {
                $mail = new PHPMailer(true);
                $mail->CharSet = 'UTF-8';
                
                // Configurar SMTP
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';
                $mail->SMTPAuth = true;
                $mail->Username = 'angelrayaviles20@gmail.com';
                $mail->Password = 'bjkk dupq lpnq ncxe';
                $mail->SMTPSecure = 'tls';
                $mail->Port = 587;
                
                // Configurar correo
                $mail->setFrom('angelrayaviles20@gmail.com', 'Formulario Agenda Web');
                $mail->addAddress('angelrayaviles20@gmail.com');
                $mail->Subject = 'Nuevo mensaje del formulario de contacto';
                $mail->Body = "Correo electrónico: $email <br>" .
                              "Nombre: $nombre <br>" .
                              "Teléfono: $telefono <br>" .
                              "Mensaje: $mensaje";
                $mail->isHTML(true);
                
                $mail->send();
                
                echo json_encode(['success' => true, 'message' => 'Mensaje enviado correctamente']);
            } catch (Exception $e) {
                echo json_encode(['success' => false, 'message' => 'Error al enviar: ' . $mail->ErrorInfo]);
            }
        }
    }
}

// Ejecutar
$controller = new ContactoController();
$controller->enviarFormulario();
?>