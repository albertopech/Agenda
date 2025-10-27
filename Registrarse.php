<?php
session_start();
include 'Conexion.php';

$mensajeError = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = isset($_POST['nombre']) ? htmlspecialchars($_POST['nombre']) : '';
    $contrasena = isset($_POST['contrasena']) ? $_POST['contrasena'] : '';
    
    // Determinar tipo de usuario: si empieza con "Admin" es admin (2), si no es estudiante (1)
    $tiposusuarioid = (strpos($nombre, "Admin") === 0) ? '2' : '1';

    if (empty($nombre) || empty($contrasena)) {
        $mensajeError = "Por favor, complete todos los campos.";
    } else {
        // Verificar si el usuario ya existe
        $checkQuery = "SELECT * FROM Usuarios WHERE nombre = ?";
        $checkStmt = mysqli_prepare($conn, $checkQuery);
        mysqli_stmt_bind_param($checkStmt, "s", $nombre);
        mysqli_stmt_execute($checkStmt);
        $result = mysqli_stmt_get_result($checkStmt);

        if (mysqli_fetch_assoc($result)) {
            $mensajeError = "El nombre de usuario ya existe.";
        } else {
            // Hash de la contraseña
            $hashedPassword = password_hash($contrasena, PASSWORD_DEFAULT);
            
            // Guardar datos en sesión para usarlos después
            $_SESSION['registro_usuario'] = [
                'nombre' => $nombre,
                'contrasena' => $hashedPassword,  // Guardar el hash
                'tiposusuarioid' => $tiposusuarioid
            ];
            
            mysqli_stmt_close($checkStmt);
            
            // Redirigir a información personal
            header("Location: Informacionpersonal.php");
            exit();
        }
        
        mysqli_stmt_close($checkStmt);
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Registrarse</title>
    <link href="Estilo2.css" rel="stylesheet"/>
</head>

<body>
    <form action="Registrarse.php" method="post">
        <h2>Registrarse</h2>

        <?php if ($mensajeError != ""): ?>
            <div class='alert alert-danger' style="background: #f8d7da; color: #721c24; padding: 10px; border-radius: 5px; margin-bottom: 15px;">
                <?= htmlspecialchars($mensajeError) ?>
            </div>
        <?php endif; ?>

        <label for="nombre"><p>Nombre Usuario:</p></label>
        <input type="text" name="nombre" class="form-control" required>

        <label for="contrasena"><p>Contraseña:</p></label>
        <input type="password" name="contrasena" class="form-control" required>

        <input type="submit" value="Registrarse" class="btn btn-1">
        
        <div class="text-center" style="margin-top: 15px;">
            <p>¿Ya tienes cuenta? <a href="Login.php" style="color: #00ADEF;">Inicia sesión</a></p>
        </div>
    </form>
</body>
</html>