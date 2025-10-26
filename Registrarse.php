<?php
session_start();
include 'Conexion.php';

$mensajeError = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = isset($_POST['nombre']) ? htmlspecialchars($_POST['nombre']) : '';
    $contrasena = isset($_POST['contrasena']) ? $_POST['contrasena'] : '';
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
            // Insertar el nuevo usuario
            $insertQuery = "INSERT INTO Usuarios (nombre, contrasenas, tiposusuariosid) VALUES (?, ?, ?)";
            $insertStmt = mysqli_prepare($conn, $insertQuery);
            $hashedPassword = password_hash($contrasena, PASSWORD_DEFAULT);
            mysqli_stmt_bind_param($insertStmt, "ssi", $nombre, $hashedPassword, $tiposusuarioid);
            
            if (mysqli_stmt_execute($insertStmt)) {
                $_SESSION['registro_usuario'] = [
                    'ID_usuarios' => mysqli_insert_id($conn),
                    'nombre' => $nombre,
                    'tiposusuarioid' => $tiposusuarioid
                ];
                
                header("Location: Informacionpersonal.php");
                exit();
            } else {
                $mensajeError = "Error al registrar el usuario.";
            }
            
            mysqli_stmt_close($insertStmt);
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
            <div class='alert alert-danger'><?= $mensajeError ?></div>
        <?php endif; ?>

        <label for="nombre"><p>Nombre Usuario:</p></label>
        <input type="text" name="nombre" class="form-control" required>

        <label for="contrasena"><p>Contraseña:</p></label>
        <input type="password" name="contrasena" class="form-control" required>

        <input type="submit" value="Registrarse" class="btn btn-1">
    </form>
</body>
</html>