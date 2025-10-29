<?php
require_once '../models/Conexion.php';

class PeriodoController {
    private $conn;
    
    public function __construct() {
        $this->conn = Database::getInstance()->getConnection();
    }
    
    // ============ EJECUTAR PROCEDIMIENTO ALMACENADO ============
    public function actualizarPeriodos() {
        $sql = "CALL ActualizarPeriodos()";
        $result = mysqli_query($this->conn, $sql);
        
        if ($result) {
            echo "Procedimiento almacenado ejecutado con éxito.\n";
        } else {
            die("Error al ejecutar procedimiento: " . mysqli_error($this->conn));
        }
    }
}

// Ejecutar
$controller = new PeriodoController();
$controller->actualizarPeriodos();
?>