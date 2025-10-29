<?php
session_start();
require_once '../models/Conexion.php';

class ActividadController {
    private $conn;
    
    public function __construct() {
        $this->conn = Database::getInstance()->getConnection();
    }
    
    // ============ LISTAR ACTIVIDADES ============
    public function index() {
        if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
            header("Location: ../views/Login.php");
            exit();
        }
        
        $user_id = $_SESSION['user_id'];
        $mensaje = $_SESSION['mensaje'] ?? "";
        unset($_SESSION['mensaje']);
        
        // Obtener actividades
        $query = "SELECT a.*, m.nombre as materia_nombre 
                  FROM ActividadesAcademicas a
                  LEFT JOIN Materia m ON a.materiaId = m.ID_materia
                  WHERE a.usuariosid = ?
                  ORDER BY a.fecha ASC";
        $stmt = mysqli_prepare($this->conn, $query);
        mysqli_stmt_bind_param($stmt, "i", $user_id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $actividades = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $actividades[] = $row;
        }
        mysqli_stmt_close($stmt);
        
        // Obtener materias
        $queryMaterias = "SELECT * FROM Materia";
        $resultMaterias = mysqli_query($this->conn, $queryMaterias);
        $materias = [];
        while ($row = mysqli_fetch_assoc($resultMaterias)) {
            $materias[] = $row;
        }
        
        include '../views/MisActividades.php';
    }
    
    // ============ CREAR ACTIVIDAD ============
    public function crear() {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $user_id = $_SESSION['user_id'];
            $materiaId = $_POST['materiaId'];
            $descripcion = $_POST['descripcion'];
            $fecha = $_POST['fecha'];
            
            $query = "INSERT INTO ActividadesAcademicas (usuariosid, materiaId, descripcion, fecha) VALUES (?, ?, ?, ?)";
            $stmt = mysqli_prepare($this->conn, $query);
            mysqli_stmt_bind_param($stmt, "iiss", $user_id, $materiaId, $descripcion, $fecha);
            
            if (mysqli_stmt_execute($stmt)) {
                $_SESSION['mensaje'] = "✅ Actividad agregada con éxito";
            } else {
                $_SESSION['mensaje'] = "❌ Error al agregar actividad";
            }
            mysqli_stmt_close($stmt);
            
            header("Location: ../views/MisActividades.php");
            exit();
        }
    }
    
    // ============ ACTUALIZAR ACTIVIDAD ============
    public function actualizar() {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $id_actividad = $_POST['id_actividad'];
            $user_id = $_SESSION['user_id'];
            $materiaId = $_POST['materiaId'];
            $descripcion = $_POST['descripcion'];
            $fecha = $_POST['fecha'];
            
            $query = "UPDATE ActividadesAcademicas SET materiaId = ?, descripcion = ?, fecha = ? WHERE ID_actividadesacademicas = ? AND usuariosid = ?";
            $stmt = mysqli_prepare($this->conn, $query);
            mysqli_stmt_bind_param($stmt, "issii", $materiaId, $descripcion, $fecha, $id_actividad, $user_id);
            
            if (mysqli_stmt_execute($stmt)) {
                $_SESSION['mensaje'] = "✅ Actividad actualizada";
            }
            mysqli_stmt_close($stmt);
            
            header("Location: ../views/MisActividades.php");
            exit();
        }
    }
    
    // ============ ELIMINAR ACTIVIDAD ============
    public function eliminar() {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $id_actividad = $_POST['id_actividad'];
            $user_id = $_SESSION['user_id'];
            
            $query = "DELETE FROM ActividadesAcademicas WHERE ID_actividadesacademicas = ? AND usuariosid = ?";
            $stmt = mysqli_prepare($this->conn, $query);
            mysqli_stmt_bind_param($stmt, "ii", $id_actividad, $user_id);
            
            if (mysqli_stmt_execute($stmt)) {
                $_SESSION['mensaje'] = "✅ Actividad eliminada";
            }
            mysqli_stmt_close($stmt);
            
            header("Location: ../views/MisActividades.php");
            exit();
        }
    }
}

// Ejecutar controlador
$controller = new ActividadController();
$action = $_GET['action'] ?? 'index';

switch($action) {
    case 'index':
        $controller->index();
        break;
    case 'crear':
        $controller->crear();
        break;
    case 'actualizar':
        $controller->actualizar();
        break;
    case 'eliminar':
        $controller->eliminar();
        break;
}
?>