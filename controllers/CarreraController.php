<?php
session_start();
require_once '../models/Conexion.php';

class CarreraController {
    private $conn;
    
    public function __construct() {
        $this->conn = Database::getInstance()->getConnection();
    }
    
    // ============ LISTAR CARRERAS ============
    public function index() {
        $query = "SELECT * FROM Carrera";
        $result = mysqli_query($this->conn, $query);
        $carreras = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $carreras[] = $row;
        }
        
        include '../views/Carrera.php';
    }
    
    // ============ CREAR CARRERA ============
    public function crear() {
        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add'])) {
            $nombre = $_POST['nombre'];
            $perfil_carrera = $_POST['perfil_carrera'];
            $duracion = $_POST['duracion'];
            $descripcion = $_POST['descripcion'];
            
            $query = "INSERT INTO Carrera (nombre, perfil_carrera, duracion, descripcion) VALUES (?, ?, ?, ?)";
            $stmt = mysqli_prepare($this->conn, $query);
            mysqli_stmt_bind_param($stmt, "ssss", $nombre, $perfil_carrera, $duracion, $descripcion);
            
            if (mysqli_stmt_execute($stmt)) {
                header("Location: ../views/Carrera.php");
                exit();
            }
            mysqli_stmt_close($stmt);
        }
    }
    
    // ============ ACTUALIZAR CARRERA ============
    public function actualizar() {
        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update'])) {
            $ID_carrera = $_POST['ID_carrera'];
            $nombre = $_POST['nombre'];
            $perfil_carrera = $_POST['perfil_carrera'];
            $duracion = $_POST['duracion'];
            $descripcion = $_POST['descripcion'];
            
            $query = "UPDATE Carrera SET nombre = ?, perfil_carrera = ?, duracion = ?, descripcion = ? WHERE ID_carrera = ?";
            $stmt = mysqli_prepare($this->conn, $query);
            mysqli_stmt_bind_param($stmt, "ssssi", $nombre, $perfil_carrera, $duracion, $descripcion, $ID_carrera);
            
            if (mysqli_stmt_execute($stmt)) {
                header("Location: ../views/Carrera.php");
                exit();
            }
            mysqli_stmt_close($stmt);
        }
    }
    
    // ============ ELIMINAR CARRERA ============
    public function eliminar() {
        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete'])) {
            $ID_carrera = $_POST['ID_carrera'];
            
            mysqli_begin_transaction($this->conn);
            
            try {
                // Actualizar referencias
                $queryUpdate = "UPDATE InformacionAcademica_estudiante SET carreraid = NULL WHERE carreraid = ?";
                $stmtUpdate = mysqli_prepare($this->conn, $queryUpdate);
                mysqli_stmt_bind_param($stmtUpdate, "i", $ID_carrera);
                mysqli_stmt_execute($stmtUpdate);
                mysqli_stmt_close($stmtUpdate);
                
                // Eliminar carrera
                $queryDelete = "DELETE FROM Carrera WHERE ID_carrera = ?";
                $stmtDelete = mysqli_prepare($this->conn, $queryDelete);
                mysqli_stmt_bind_param($stmtDelete, "i", $ID_carrera);
                mysqli_stmt_execute($stmtDelete);
                mysqli_stmt_close($stmtDelete);
                
                mysqli_commit($this->conn);
                header("Location: ../views/Carrera.php");
                exit();
            } catch (Exception $e) {
                mysqli_rollback($this->conn);
                die("Error: " . $e->getMessage());
            }
        }
    }
}

// Ejecutar
$controller = new CarreraController();
$action = $_GET['action'] ?? 'index';

switch($action) {
    case 'crear':
        $controller->crear();
        break;
    case 'actualizar':
        $controller->actualizar();
        break;
    case 'eliminar':
        $controller->eliminar();
        break;
    default:
        $controller->index();
        break;
}
?>