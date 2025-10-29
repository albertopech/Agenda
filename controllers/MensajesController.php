<?php
require_once '../models/Conexion.php';
require_once '../PHPMailer/src/PHPMailer.php';
require_once '../PHPMailer/src/SMTP.php';
require_once '../PHPMailer/src/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class MensajesController {
    private $conn;
    
    public function __construct() {
        $this->conn = Database::getInstance()->getConnection();
    }
    
    // ============ ENVIAR CORREOS SI HAY ACTIVIDADES PRÓXIMAS ============
    public function enviarCorreosSiActividadProxima() {
        // Verificar si hay actividades que vencen mañana
        $sqlActividades = "SELECT COUNT(*) as actividadesProximas 
                          FROM ActividadesAcademicas 
                          WHERE fecha = DATE_ADD(CURDATE(), INTERVAL 1 DAY)";
        $resultActividades = mysqli_query($this->conn, $sqlActividades);
        $rowActividades = mysqli_fetch_assoc($resultActividades);
        
        if ($rowActividades['actividadesProximas'] > 0) {
            // Obtener correos electrónicos
            $sqlEmails = "SELECT email FROM InformacionPersonal WHERE email IS NOT NULL AND email != ''";
            $resultEmails = mysqli_query($this->conn, $sqlEmails);
            
            while ($rowEmail = mysqli_fetch_assoc($resultEmails)) {
                $destinatario = $rowEmail['email'];
                
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
                    $mail->setFrom('angelrayaviles20@gmail.com', 'Administrador');
                    $mail->addAddress($destinatario);
                    $mail->Subject = 'Actividad académica próxima a vencer';
                    $mail->Body = 'Tienes una actividad académica que está próxima a vencer. Por favor revisa tu agenda.';
                    
                    $mail->send();
                    echo "Correo enviado correctamente a {$destinatario}<br>";
                } catch (Exception $e) {
                    echo "Error al enviar el correo a {$destinatario}: " . $mail->ErrorInfo . "<br>";
                }
            }
        } else {
            echo "No hay actividades próximas a vencer.";
        }
    }
}

// Ejecutar
$controller = new MensajesController();
$controller->enviarCorreosSiActividadProxima();
?>