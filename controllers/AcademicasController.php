<?php
session_start();
require_once '../models/Conexion.php';

class AcademicasController {
    private $conn;
    
    public function __construct() {
        $this->conn = Database::getInstance()->getConnection();
    }
    
    // ============ MOSTRAR PORTAL ACADÉMICO ============
    public function index() {
        // Verificar sesión
        if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
            header("Location: ../views/Login.php");
            exit();
        }
        
        $user_id = $_SESSION['user_id'];
        
        // Obtener información del usuario
        $query = "SELECT u.nombre, u.tiposusuariosid, ip.nombres, ip.primerapellido, ip.segundoapellido, ip.email 
                  FROM Usuarios u 
                  LEFT JOIN InformacionPersonal ip ON u.ID_usuarios = ip.usuariosid 
                  WHERE u.ID_usuarios = ?";
        $stmt = mysqli_prepare($this->conn, $query);
        mysqli_stmt_bind_param($stmt, "i", $user_id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $usuario = mysqli_fetch_assoc($result);
        mysqli_stmt_close($stmt);
        
        // Obtener información académica
        $queryAcademica = "SELECT iae.*, c.nombre as carrera_nombre, p.nombre as periodo_nombre 
                           FROM InformacionAcademica_estudiante iae 
                           LEFT JOIN Carrera c ON iae.carreraId = c.ID_carrera 
                           LEFT JOIN Periodo p ON iae.periodoid = p.ID_periodo 
                           WHERE iae.usuariosid = ?";
        $stmtAcademica = mysqli_prepare($this->conn, $queryAcademica);
        mysqli_stmt_bind_param($stmtAcademica, "i", $user_id);
        mysqli_stmt_execute($stmtAcademica);
        $resultAcademica = mysqli_stmt_get_result($stmtAcademica);
        $infoAcademica = mysqli_fetch_assoc($resultAcademica);
        mysqli_stmt_close($stmtAcademica);
        
        // Obtener materias del estudiante
        $materias = [];
        if (isset($infoAcademica['carreraId'])) {
            $queryMaterias = "SELECT m.nombre, cm.semestre 
                              FROM Carrera_Materia cm 
                              LEFT JOIN Materia m ON cm.materiaid = m.ID_materia 
                              WHERE cm.carreraid = ? 
                              ORDER BY cm.semestre";
            $stmtMaterias = mysqli_prepare($this->conn, $queryMaterias);
            mysqli_stmt_bind_param($stmtMaterias, "i", $infoAcademica['carreraId']);
            mysqli_stmt_execute($stmtMaterias);
            $resultMaterias = mysqli_stmt_get_result($stmtMaterias);
            while ($row = mysqli_fetch_assoc($resultMaterias)) {
                $materias[] = $row;
            }
            mysqli_stmt_close($stmtMaterias);
        }
        
        // Cargar vista
        include '../views/Academicas.php';
    }
}

// Ejecutar controlador
$controller = new AcademicasController();
$controller->index();
?>