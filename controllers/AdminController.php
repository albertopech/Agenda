<?php
session_start();
require_once '../models/Conexion.php';

class AdminController {
    private $conn;
    
    public function __construct() {
        $this->conn = Database::getInstance()->getConnection();
    }
    
    // ============ MOSTRAR PANEL ADMIN ============
    public function index() {
        // Verificar que sea administrador
        if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
            header("Location: ../views/Login.php");
            exit();
        }
        
        if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] != 2) {
            header("Location: ../views/index.php");
            exit();
        }
        
        // Cargar vista
        include '../views/Admin.php';
    }
}

// Ejecutar controlador
$controller = new AdminController();
$controller->index();
?>