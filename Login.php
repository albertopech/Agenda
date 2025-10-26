<?php
include 'Conexion.php';  // Incluye el archivo que maneja la conexión a la base de datos.
session_start();  // Inicia una nueva sesión o reanuda la sesión existente.

$mensajeError = "";  // Variable para almacenar mensajes de error que podrían surgir durante el proceso de inicio de sesión.

// Comprueba si el formulario fue enviado usando el método POST.
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recupera el nombre de usuario y la contraseña del formulario, asegurándose de escapar caracteres especiales para prevenir inyecciones XSS.
    $nombre = isset($_POST['nombre']) ? htmlspecialchars($_POST['nombre']) : '';
    $contrasenas = isset($_POST['contrasenas']) ? $_POST['contrasenas'] : '';

    // Verifica si el nombre de usuario o la contraseña están vacíos.
    if (empty($nombre) || empty($contrasenas)) {
        $mensajeError = "Por favor, ingrese nombre de usuario y contraseña.";
    } else {
        // Prepara una consulta SQL para buscar el usuario por nombre.
        $query = "SELECT * FROM Usuarios WHERE nombre = ? LIMIT 1";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "s", $nombre);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if ($result !== false) {
            // Si la consulta es exitosa, recupera los datos del usuario.
            if ($row = mysqli_fetch_assoc($result)) {
                $hashContrasena = $row['contrasenas'];  // Almacena el hash de la contraseña recuperada de la base de datos.
                
                // Verifica la contraseña ingresada contra el hash almacenado.
                if (password_verify($contrasenas, $hashContrasena)) {
                    // Si la contraseña es correcta, establece variables de sesión.
                    $_SESSION['logged_in'] = true;
                    $_SESSION['user_id'] = $row['ID_usuarios'];

                    // Recupera el tipo de usuario desde la base de datos basándose en el ID de tipos de usuario.
                    $tiposusuariosid = $row['tiposusuariosid'];
                    $queryTipo = "SELECT tipo FROM Tiposusuarios WHERE ID_tiposusuarios = ?";
                    $stmtTipo = mysqli_prepare($conn, $queryTipo);
                    mysqli_stmt_bind_param($stmtTipo, "i", $tiposusuariosid);
                    mysqli_stmt_execute($stmtTipo);
                    $resultTipo = mysqli_stmt_get_result($stmtTipo);
                    
                    if ($rowTipo = mysqli_fetch_assoc($resultTipo)) {
                        $tipo = $rowTipo['tipo'];  // Obtiene el tipo de usuario.
                        $_SESSION['user_type'] = $tipo;  // Guarda el tipo de usuario en la sesión.
                        
                        // Redirecciona al usuario a diferentes páginas basadas en su tipo.
                        if ($tipo == 'Admi') {
                            header("Location: Admin.php");
                            exit();
                        }
                    }
                    
                    mysqli_stmt_close($stmtTipo);
                    
                    // Redirecciona al usuario a la página principal si no es administrador.
                    header("Location: Academicas.php");
                    exit();
                } else {
                    // Si la contraseña no coincide, muestra un mensaje de error.
                    $mensajeError = "Nombre de usuario o contraseña incorrectos.";
                }
            } else {
                // Si no se encuentra el usuario, muestra un mensaje de error.
                $mensajeError = "Nombre de usuario o contraseña incorrectos.";
            }
            
            mysqli_stmt_close($stmt);
        } else {
            // Si la consulta falla, muestra los errores.
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