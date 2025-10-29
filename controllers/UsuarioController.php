<?php
session_start();
require_once '../models/Conexion.php';

class UsuarioController {
    private $conn;
    
    public function __construct() {
        $this->conn = Database::getInstance()->getConnection();
    }
    
    // ============ GUARDAR INFORMACIÓN PERSONAL ============
    public function guardarInformacionPersonal() {
        $mensajeError = "";
        
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $nombres = htmlspecialchars($_POST['nombres'] ?? '');
            $primerapellido = htmlspecialchars($_POST['primerapellido'] ?? '');
            $segundoapellido = htmlspecialchars($_POST['segundoapellido'] ?? '');
            $fecha_nacimiento = $_POST['fecha_nacimiento'] ?? '';
            $telefono = $_POST['telefono'] ?? '';
            $email = htmlspecialchars($_POST['email'] ?? '');
            $RFC = $_POST['RFC'] ?? null;
            
            // Validar edad
            if (!empty($fecha_nacimiento)) {
                $fechaNacimiento = new DateTime($fecha_nacimiento);
                $fechaActual = new DateTime();
                $edad = $fechaActual->diff($fechaNacimiento)->y;
                
                if ($edad < 18) {
                    $mensajeError = "Debes tener al menos 18 años para registrarte.";
                }
            }
            
            if (empty($mensajeError)) {
                if (empty($nombres) || empty($primerapellido) || empty($telefono) || empty($email)) {
                    $mensajeError = "Por favor, complete todos los campos obligatorios.";
                } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    $mensajeError = "Ingrese una dirección de correo electrónico válida.";
                }
            }
            
            if (empty($mensajeError)) {
                $_SESSION['informacion_personal'] = [
                    'nombres' => $nombres,
                    'primerapellido' => $primerapellido,
                    'segundoapellido' => $segundoapellido,
                    'fecha_nacimiento' => $fecha_nacimiento,
                    'telefono' => $telefono,
                    'email' => $email,
                    'RFC' => $RFC
                ];
                
                header("Location: ../views/Informacioncontacto.php");
                exit();
            }
        }
        
        include '../views/Informacionpersonal.php';
    }
    
    // ============ GUARDAR INFORMACIÓN DE CONTACTO ============
    public function guardarInformacionContacto() {
        $mensajeError = "";
        
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $codigo_postal = $_POST["codigo_postal"] ?? '';
            $municipio = htmlspecialchars($_POST["municipio"] ?? '');
            $estado = htmlspecialchars($_POST["estado"] ?? '');
            $ciudad = htmlspecialchars($_POST["ciudad"] ?? '');
            $colonia = htmlspecialchars($_POST["colonia"] ?? '');
            $calle_principal = htmlspecialchars($_POST["calle_principal"] ?? '');
            $primer_cruzamiento = htmlspecialchars($_POST["primer_cruzamiento"] ?? '');
            $segundo_cruzamiento = htmlspecialchars($_POST["segundo_cruzamiento"] ?? '');
            $referencias = htmlspecialchars($_POST["referencias"] ?? '');
            $numero_exterior = $_POST["numero_exterior"] ?? '';
            $numero_interior = $_POST["numero_interior"] ?? '';
            
            // Validaciones
            if (empty($municipio) || empty($estado) || empty($ciudad) || empty($colonia) || empty($calle_principal)) {
                $mensajeError = "Por favor, complete todos los campos obligatorios.";
            } elseif (!is_numeric($codigo_postal) || strlen($codigo_postal) !== 5) {
                $mensajeError = "Ingrese un código postal válido de 5 dígitos.";
            } else {
                $_SESSION['informacion_contacto'] = [
                    'codigo_postal' => $codigo_postal,
                    'municipio' => $municipio,
                    'estado' => $estado,
                    'ciudad' => $ciudad,
                    'colonia' => $colonia,
                    'calle_principal' => $calle_principal,
                    'primer_cruzamiento' => $primer_cruzamiento,
                    'segundo_cruzamiento' => $segundo_cruzamiento,
                    'referencias' => $referencias,
                    'numero_exterior' => $numero_exterior,
                    'numero_interior' => $numero_interior
                ];
                
                header("Location: ../views/InformacionAcademica_estudiante.php");
                exit();
            }
        }
        
        // Obtener último usuario
        $lastUserIdQuery = "SELECT ID_usuarios FROM Usuarios ORDER BY ID_usuarios DESC LIMIT 1";
        $lastUserIdResult = mysqli_query($this->conn, $lastUserIdQuery);
        $lastUser = mysqli_fetch_assoc($lastUserIdResult);
        
        include '../views/Informacioncontacto.php';
    }
    
    // ============ COMPLETAR REGISTRO ACADÉMICO ============
    public function completarRegistro() {
        $mensaje = "";
        
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $periodoId = $_POST["periodoId"] ?? '';
            $carreraId = $_POST["carreraId"] ?? '';
            $numcontrol = $_POST["numcontrol"] ?? '';
            $semestre = $_POST["semestre"] ?? '';
            $promedio = $_POST["promedio"] ?? '';
            
            // Validaciones
            if (empty($periodoId) || empty($carreraId) || empty($numcontrol) || empty($semestre) || empty($promedio)) {
                $mensaje = "Por favor, complete todos los campos obligatorios.";
            } elseif (!is_numeric($semestre) || $semestre < 1 || $semestre > 12) {
                $mensaje = "El semestre debe ser un número válido entre 1 y 12.";
            } elseif (!preg_match('/^\d{2}\.\d$/', $promedio)) {
                $mensaje = "El promedio debe tener el formato correcto, por ejemplo, 90.0.";
            } elseif (!preg_match('/^\d{8}$/', $numcontrol)) {
                $mensaje = "El número de control debe tener 8 dígitos.";
            } else {
                // Verificar datos en sesión
                if (!isset($_SESSION['registro_usuario']) || !isset($_SESSION['informacion_personal']) || !isset($_SESSION['informacion_contacto'])) {
                    $mensaje = "Error: Datos de sesión incompletos.";
                } else {
                    // Iniciar transacción
                    mysqli_begin_transaction($this->conn);
                    
                    try {
                        // 1. Insertar Usuario
                        $usuario = $_SESSION['registro_usuario'];
                        $hashedPassword = password_hash($usuario['contrasena'], PASSWORD_DEFAULT);
                        
                        $queryUsuario = "INSERT INTO Usuarios (nombre, contrasenas, tiposusuariosid) VALUES (?, ?, ?)";
                        $stmtUsuario = mysqli_prepare($this->conn, $queryUsuario);
                        mysqli_stmt_bind_param($stmtUsuario, "ssi", $usuario['nombre'], $hashedPassword, $usuario['tiposusuarioid']);
                        mysqli_stmt_execute($stmtUsuario);
                        $usuariosid = mysqli_insert_id($this->conn);
                        mysqli_stmt_close($stmtUsuario);
                        
                        // 2. Insertar Información Personal
                        $personal = $_SESSION['informacion_personal'];
                        $queryPersonal = "INSERT INTO InformacionPersonal (usuariosid, nombres, primerapellido, segundoapellido, fecha_nacimiento, telefono, email, RFC) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
                        $stmtPersonal = mysqli_prepare($this->conn, $queryPersonal);
                        mysqli_stmt_bind_param($stmtPersonal, "isssssss", $usuariosid, $personal['nombres'], $personal['primerapellido'], $personal['segundoapellido'], $personal['fecha_nacimiento'], $personal['telefono'], $personal['email'], $personal['RFC']);
                        mysqli_stmt_execute($stmtPersonal);
                        mysqli_stmt_close($stmtPersonal);
                        
                        // 3. Insertar Información de Contacto
                        $contacto = $_SESSION['informacion_contacto'];
                        $queryContacto = "INSERT INTO InformacionContacto (usuariosid, codigo_postal, municipio, estado, ciudad, colonia, calle_principal, primer_cruzamiento, segundo_cruzamiento, referencias, numero_exterior, numero_interior) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                        $stmtContacto = mysqli_prepare($this->conn, $queryContacto);
                        mysqli_stmt_bind_param($stmtContacto, "isssssssssss", $usuariosid, $contacto['codigo_postal'], $contacto['municipio'], $contacto['estado'], $contacto['ciudad'], $contacto['colonia'], $contacto['calle_principal'], $contacto['primer_cruzamiento'], $contacto['segundo_cruzamiento'], $contacto['referencias'], $contacto['numero_exterior'], $contacto['numero_interior']);
                        mysqli_stmt_execute($stmtContacto);
                        mysqli_stmt_close($stmtContacto);
                        
                        // 4. Insertar Información Académica
                        $queryAcademica = "INSERT INTO InformacionAcademica_estudiante (usuariosid, periodoid, carreraId, numcontrol, semestre, promedio) VALUES (?, ?, ?, ?, ?, ?)";
                        $stmtAcademica = mysqli_prepare($this->conn, $queryAcademica);
                        mysqli_stmt_bind_param($stmtAcademica, "iiisid", $usuariosid, $periodoId, $carreraId, $numcontrol, $semestre, $promedio);
                        mysqli_stmt_execute($stmtAcademica);
                        mysqli_stmt_close($stmtAcademica);
                        
                        // Confirmar transacción
                        mysqli_commit($this->conn);
                        
                        // Limpiar sesiones
                        unset($_SESSION['registro_usuario']);
                        unset($_SESSION['informacion_personal']);
                        unset($_SESSION['informacion_contacto']);
                        
                        header("Location: ../views/Login.php?registro=exitoso");
                        exit();
                        
                    } catch (Exception $e) {
                        mysqli_rollback($this->conn);
                        $mensaje = "Error en el registro: " . $e->getMessage();
                    }
                }
            }
        }
        
        // Obtener periodos y carreras
        $queryPeriodos = "SELECT * FROM Periodo";
        $resultPeriodos = mysqli_query($this->conn, $queryPeriodos);
        $periodos = [];
        while($row = mysqli_fetch_assoc($resultPeriodos)) {
            $periodos[] = $row;
        }
        
        $queryCarreras = "SELECT * FROM Carrera";
        $resultCarreras = mysqli_query($this->conn, $queryCarreras);
        $carreras = [];
        while($row = mysqli_fetch_assoc($resultCarreras)) {
            $carreras[] = $row;
        }
        
        include '../views/InformacionAcademica_estudiante.php';
    }
}

// Ejecutar según acción
$controller = new UsuarioController();
$action = $_GET['action'] ?? 'informacion_personal';

switch($action) {
    case 'informacion_personal':
        $controller->guardarInformacionPersonal();
        break;
    case 'informacion_contacto':
        $controller->guardarInformacionContacto();
        break;
    case 'completar_registro':
        $controller->completarRegistro();
        break;
}
?>