<?php
session_start();
require_once '../models/Conexion.php';

class AuthController {
    private $conn;
    
    public function __construct() {
        $this->conn = Database::getInstance()->getConnection();
    }
    
    // ============ LOGIN ============
    public function login() {
        $mensajeError = "";
        
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $nombre = htmlspecialchars($_POST['nombre'] ?? '');
            $contrasena = $_POST['contrasenas'] ?? '';
            
            if (empty($nombre) || empty($contrasena)) {
                $mensajeError = "Por favor, ingrese nombre de usuario y contraseña.";
            } else {
                // Consulta SQL
                $query = "SELECT * FROM Usuarios WHERE nombre = ? LIMIT 1";
                $stmt = mysqli_prepare($this->conn, $query);
                mysqli_stmt_bind_param($stmt, "s", $nombre);
                mysqli_stmt_execute($stmt);
                $result = mysqli_stmt_get_result($stmt);
                
                if ($row = mysqli_fetch_assoc($result)) {
                    if (password_verify($contrasena, $row['contrasenas'])) {
                        $_SESSION['logged_in'] = true;
                        $_SESSION['user_id'] = $row['ID_usuarios'];
                        
                        // Obtener tipo de usuario
                        $tiposusuariosid = $row['tiposusuariosid'];
                        $queryTipo = "SELECT tipo FROM Tiposusuarios WHERE ID_tiposusuarios = ?";
                        $stmtTipo = mysqli_prepare($this->conn, $queryTipo);
                        mysqli_stmt_bind_param($stmtTipo, "i", $tiposusuariosid);
                        mysqli_stmt_execute($stmtTipo);
                        $resultTipo = mysqli_stmt_get_result($stmtTipo);
                        
                        if ($rowTipo = mysqli_fetch_assoc($resultTipo)) {
                            $_SESSION['user_type'] = $rowTipo['tipo'];
                            
                            if ($rowTipo['tipo'] == 'Admi') {
                                header("Location: ../views/Admin.php");
                                exit();
                            }
                        }
                        mysqli_stmt_close($stmtTipo);
                        
                        header("Location: ../views/index.php");
                        exit();
                    } else {
                        $mensajeError = "Nombre de usuario o contraseña incorrectos.";
                    }
                } else {
                    $mensajeError = "Nombre de usuario o contraseña incorrectos.";
                }
                mysqli_stmt_close($stmt);
            }
        }
        
        // Guardar el directorio actual
        $currentDir = getcwd();
        
        // Cambiar al directorio views para que las rutas relativas funcionen
        chdir(__DIR__ . '/../views');
        
        // Cargar vista
        include 'Login.php';
        
        // Restaurar el directorio original
        chdir($currentDir);
    }
    
    // ============ REGISTRO ============
    public function registro() {
        $mensajeError = "";
        
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $nombre = htmlspecialchars($_POST['nombre'] ?? '');
            $contrasena = $_POST['contrasena'] ?? '';
            
            if (empty($nombre) || empty($contrasena)) {
                $mensajeError = "Por favor, complete todos los campos.";
            } else {
                // Verificar si usuario existe
                $checkQuery = "SELECT * FROM Usuarios WHERE nombre = ?";
                $checkStmt = mysqli_prepare($this->conn, $checkQuery);
                mysqli_stmt_bind_param($checkStmt, "s", $nombre);
                mysqli_stmt_execute($checkStmt);
                $result = mysqli_stmt_get_result($checkStmt);
                
                if (mysqli_fetch_assoc($result)) {
                    $mensajeError = "El nombre de usuario ya existe.";
                } else {
                    // Determinar tipo de usuario
                    $tiposusuarioid = (strpos($nombre, "Admin") === 0) ? '2' : '1';
                    
                    // Guardar en sesión
                    $_SESSION['registro_usuario'] = [
                        'nombre' => $nombre,
                        'contrasena' => $contrasena,
                        'tiposusuarioid' => $tiposusuarioid
                    ];
                    
                    mysqli_stmt_close($checkStmt);
                    header("Location: ../views/Informacionpersonal.php");
                    exit();
                }
                mysqli_stmt_close($checkStmt);
            }
        }
        
        // Guardar el directorio actual
        $currentDir = getcwd();
        
        // Cambiar al directorio views para que las rutas relativas funcionen
        chdir(__DIR__ . '/../views');
        
        include 'Registrarse.php';
        
        // Restaurar el directorio original
        chdir($currentDir);
    }
    
    // ============ LOGOUT ============
    public function logout() {
        session_destroy();
        header("Location: ../views/index.php");
        exit();
    }
}

// Ejecutar controlador
$controller = new AuthController();
$action = $_GET['action'] ?? 'login';

switch($action) {
    case 'login':
        $controller->login();
        break;
    case 'registro':
        $controller->registro();
        break;
    case 'logout':
        $controller->logout();
        break;
}
?>