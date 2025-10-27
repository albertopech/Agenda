<?php
include 'Conexion.php';
session_start();

$mensajeError = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = isset($_POST['nombre']) ? htmlspecialchars($_POST['nombre']) : '';
    $contrasenas = isset($_POST['contrasenas']) ? $_POST['contrasenas'] : '';

    if (empty($nombre) || empty($contrasenas)) {
        $mensajeError = "Por favor, ingrese nombre de usuario y contraseña.";
    } else {
        $query = "SELECT * FROM Usuarios WHERE nombre = ? LIMIT 1";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "s", $nombre);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if ($result !== false) {
            if ($row = mysqli_fetch_assoc($result)) {
                $hashContrasena = $row['contrasenas'];
                
                if (password_verify($contrasenas, $hashContrasena)) {
                    $_SESSION['logged_in'] = true;
                    $_SESSION['user_id'] = $row['ID_usuarios'];

                    $tiposusuariosid = $row['tiposusuariosid'];
                    $queryTipo = "SELECT tipo FROM Tiposusuarios WHERE ID_tiposusuarios = ?";
                    $stmtTipo = mysqli_prepare($conn, $queryTipo);
                    mysqli_stmt_bind_param($stmtTipo, "i", $tiposusuariosid);
                    mysqli_stmt_execute($stmtTipo);
                    $resultTipo = mysqli_stmt_get_result($stmtTipo);
                    
                    if ($rowTipo = mysqli_fetch_assoc($resultTipo)) {
                        $tipo = $rowTipo['tipo'];
                        $_SESSION['user_type'] = $tipo;
                        
                        if ($tipo == 'Admi') {
                            header("Location: Admin.php");
                            exit();
                        }
                    }
                    
                    mysqli_stmt_close($stmtTipo);
                    
                    // Redirigir a index.php en lugar de Academicas.php
                    header("Location: index.php");
                    exit();
                } else {
                    $mensajeError = "Nombre de usuario o contraseña incorrectos.";
                }
            } else {
                $mensajeError = "Nombre de usuario o contraseña incorrectos.";
            }
            
            mysqli_stmt_close($stmt);
        } else {
            die("Error en la consulta: " . mysqli_error($conn));
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="Estilo2.css" rel="stylesheet">
</head>
<body>
    
    <form action="Login.php" method="post">
        <h2>Iniciar sesión</h2>

        <?php if ($mensajeError != ""): ?>
            <div class='alert alert-danger'><?= $mensajeError ?></div>
        <?php endif; ?>

        <label for="nombre"><p>Usuario:</p></label>
        <input type="text" name="nombre" required>           
       
        <label for="contrasenas"><p>Contraseña:</p></label>
        <input type="password" name="contrasenas" required>
        
        <input type="submit" value="Iniciar sesión" class="btn btn-1">
     
        <div class="text-center">
            <label for="registrarse"><p>No tienes cuenta?</p></label>
            <a href="Registrarse.php" class="btn btn-link">Registrate</a>
        </div>
    </form>

</body>
</html>