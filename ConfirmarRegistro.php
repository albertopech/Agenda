<?php
session_start();  // Inicia la sesión

include 'Conexion.php';  // Incluye el archivo de conexión a la base de datos.

if (!isset($_SESSION['temp_user'])) {
    header("Location: Registrarse.php");  // Redirige si no hay datos temporales
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {  // Comprueba si el formulario ha sido enviado usando POST.
    if (hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {  // Verifica el token CSRF
        $tempUser = $_SESSION['temp_user'];

        // Insertar el usuario en la base de datos
        $query = "INSERT INTO dbo.Usuarios (nombre, contrasenas, tiposusuariosid) VALUES (?, ?, ?)";
        $params = array($tempUser['nombre'], $tempUser['contrasena'], $tempUser['tiposusuarioid']);
        $stmt = sqlsrv_query($conn, $query, $params);

        if ($stmt === false) {
            die(print_r(sqlsrv_errors(), true));
        } else {
            unset($_SESSION['temp_user']);  // Eliminar los datos temporales
            header("Location: Informacionpersonal.php");
            exit();
        }
    } else {
        $mensajeError = "Error de validación del formulario.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Confirmar Registro</title>
    <link href="Estilo2.css" rel="stylesheet"/>
</head>
<body>
    <form action="ConfirmarRegistro.php" method="post">
        <h2>Confirmar Registro</h2>

        <?php if (!empty($mensajeError)): ?>
            <div class='alert alert-danger'><?= htmlspecialchars($mensajeError) ?></div>
        <?php endif; ?>

        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">

        <p>Nombre Usuario: <?= htmlspecialchars($_SESSION['temp_user']['nombre']) ?></p>

        <button type="submit" class="button-custom">Confirmar Registro</button>
    </form>
</body>
</html>
