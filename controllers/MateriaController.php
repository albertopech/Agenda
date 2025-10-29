<?php
session_start();
require_once '../models/Conexion.php';

class MateriaController {
    private $conn;
    
    public function __construct() {
        $this->conn = Database::getInstance()->getConnection();
    }
    
    // ============ LISTAR MATERIAS ============
    public function index() {
        // Obtener materias
        $query = "SELECT * FROM Materia";
        $result = mysqli_query($this->conn, $query);
        $materias = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $materias[] = $row;
        }
        
        // Obtener periodos
        $queryPeriodos = "SELECT * FROM Periodo";
        $resultPeriodos = mysqli_query($this->conn, $queryPeriodos);
        $periodos = [];
        while($row = mysqli_fetch_assoc($resultPeriodos)) {
            $periodos[] = $row;
        }
        
        // Obtener carreras
        $queryCarreras = "SELECT * FROM Carrera";
        $resultCarreras = mysqli_query($this->conn, $queryCarreras);
        $carreras = [];
        while ($row = mysqli_fetch_assoc($resultCarreras)) {
            $carreras[] = $row;
        }
        
        // Obtener carrera_materia
        $queryCarreraMateria = "SELECT * FROM Carrera_Materia";
        $resultCarreraMateria = mysqli_query($this->conn, $queryCarreraMateria);
        $carrera_materia = [];
        while ($row = mysqli_fetch_assoc($resultCarreraMateria)) {
            $carrera_materia[] = $row;
        }
        
        include '../views/Materia.php';
    }
    
    // ============ CREAR MATERIA ============
    public function crear() {
        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add'])) {
            $nombre = $_POST['nombre'];
            $periodoId = isset($_POST["periodoId"]) && $_POST["periodoId"] != '' ? $_POST["periodoId"] : null;
            $carreraId = isset($_POST["carreraId"]) && $_POST["carreraId"] != '' ? $_POST["carreraId"] : null;
            $es_reticula = isset($_POST["es_reticula"]) ? $_POST["es_reticula"] : null;
            
            mysqli_begin_transaction($this->conn);
            
            try {
                // Insertar materia
                $queryMateria = "INSERT INTO Materia (nombre, periodoId, carreraId) VALUES (?, ?, ?)";
                $stmtMateria = mysqli_prepare($this->conn, $queryMateria);
                mysqli_stmt_bind_param($stmtMateria, "sii", $nombre, $periodoId, $carreraId);
                mysqli_stmt_execute($stmtMateria);
                $materiaId = mysqli_insert_id($this->conn);
                mysqli_stmt_close($stmtMateria);
                
                // Insertar en carrera_materia
                $queryCarreraMateria = "INSERT INTO Carrera_Materia (carreraid, materiaid, es_reticula) VALUES (?, ?, ?)";
                $stmtCarreraMateria = mysqli_prepare($this->conn, $queryCarreraMateria);
                mysqli_stmt_bind_param($stmtCarreraMateria, "iii", $carreraId, $materiaId, $es_reticula);
                mysqli_stmt_execute($stmtCarreraMateria);
                mysqli_stmt_close($stmtCarreraMateria);
                
                mysqli_commit($this->conn);
                header("Location: ../views/Materia.php");
                exit();
            } catch (Exception $e) {
                mysqli_rollback($this->conn);
                die("Error: " . $e->getMessage());
            }
        }
    }
    
    // ============ ACTUALIZAR MATERIA ============
    public function actualizar() {
        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update'])) {
            $ID_materia = $_POST['ID_materia'];
            $periodoId = $_POST["periodoId"];
            $carreraId = $_POST["carreraId"];
            $nombre = $_POST['nombre'];
            
            mysqli_begin_transaction($this->conn);
            
            try {
                // Actualizar materia
                $queryMateria = "UPDATE Materia SET periodoId = ?, carreraId = ?, nombre = ? WHERE ID_materia = ?";
                $stmtMateria = mysqli_prepare($this->conn, $queryMateria);
                mysqli_stmt_bind_param($stmtMateria, "iisi", $periodoId, $carreraId, $nombre, $ID_materia);
                mysqli_stmt_execute($stmtMateria);
                mysqli_stmt_close($stmtMateria);
                
                // Actualizar carrera_materia
                $queryCarreraMateria = "UPDATE Carrera_Materia SET carreraid = ? WHERE materiaid = ?";
                $stmtCarreraMateria = mysqli_prepare($this->conn, $queryCarreraMateria);
                mysqli_stmt_bind_param($stmtCarreraMateria, "ii", $carreraId, $ID_materia);
                mysqli_stmt_execute($stmtCarreraMateria);
                mysqli_stmt_close($stmtCarreraMateria);
                
                mysqli_commit($this->conn);
                header("Location: ../views/Materia.php");
                exit();
            } catch (Exception $e) {
                mysqli_rollback($this->conn);
                die("Error: " . $e->getMessage());
            }
        }
    }
    
    // ============ ELIMINAR MATERIA ============
    public function eliminar() {
        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete'])) {
            $ID_materia = $_POST['ID_materia'];
            
            mysqli_begin_transaction($this->conn);
            
            try {
                // Eliminar actividades
                $queryActividades = "DELETE FROM ActividadesAcademicas WHERE materiaID = ?";
                $stmtActividades = mysqli_prepare($this->conn, $queryActividades);
                mysqli_stmt_bind_param($stmtActividades, "i", $ID_materia);
                mysqli_stmt_execute($stmtActividades);
                mysqli_stmt_close($stmtActividades);
                
                // Eliminar de carrera_materia
                $queryCarreraMateria = "DELETE FROM Carrera_Materia WHERE materiaid = ?";
                $stmtCarreraMateria = mysqli_prepare($this->conn, $queryCarreraMateria);
                mysqli_stmt_bind_param($stmtCarreraMateria, "i", $ID_materia);
                mysqli_stmt_execute($stmtCarreraMateria);
                mysqli_stmt_close($stmtCarreraMateria);
                
                // Eliminar materia
                $queryMateria = "DELETE FROM Materia WHERE ID_materia = ?";
                $stmtMateria = mysqli_prepare($this->conn, $queryMateria);
                mysqli_stmt_bind_param($stmtMateria, "i", $ID_materia);
                mysqli_stmt_execute($stmtMateria);
                mysqli_stmt_close($stmtMateria);
                
                mysqli_commit($this->conn);
                header("Location: ../views/Materia.php");
                exit();
            } catch (Exception $e) {
                mysqli_rollback($this->conn);
                die("Error: " . $e->getMessage());
            }
        }
    }
}

// Ejecutar
$controller = new MateriaController();
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