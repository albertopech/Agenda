<?php
session_start();  // Inicia la sesión

include 'Conexion.php';

if (!isset($_SESSION['informacion_personal'])) {
    header("Location: Informacionpersonal.php");  // Redirige si no hay datos temporales
    exit();
}

$informacionPersonal = $_SESSION['informacion_personal'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $query = "INSERT INTO dbo.InformacionPersonal (usuariosid, nombres, primerapellido, segundoapellido, fecha_nacimiento, telefono, email, RFC) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $params = array(
        $informacionPersonal['usuariosid'], 
        $informacionPersonal['nombres'], 
        $informacionPersonal['primerapellido'], 
        $informacionPersonal['segundoapellido'], 
        $informacionPersonal['fecha_nacimiento'], 
        $informacionPersonal['telefono'], 
        $informacionPersonal['email'], 
        $informacionPersonal['RFC']
    );
    $stmt = sqlsrv_query($conn, $query, $params);

    if ($stmt === false) {
        die(print_r(sqlsrv_errors(), true));
    } else {
        unset($_SESSION['informacion_personal']);  // Eliminar los datos temporales
        header("Location: Informacioncontacto.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirmar Información Personal</title>
    <link href="Estilo2.css" rel="stylesheet"/>
</head>
<body>
    <form action="ConfirmarInformacion.php" method="post">
        <h2>Confirmar Información Personal</h2>

        <p>Nombres: <?= htmlspecialchars($informacionPersonal['nombres']) ?></p>
        <p>Primer Apellido: <?= htmlspecialchars($informacionPersonal['primerapellido']) ?></p>
        <p>Segundo Apellido: <?= htmlspecialchars($informacionPersonal['segundoapellido']) ?></p>
        <p>Fecha de Nacimiento: <?= htmlspecialchars($informacionPersonal['fecha_nacimiento']) ?></p>
        <p>Teléfono: <?= htmlspecialchars($informacionPersonal['telefono']) ?></p>
        <p>Email: <?= htmlspecialchars($informacionPersonal['email']) ?></p>
        <?php if ($informacionPersonal['RFC']): ?>
            <p>RFC: <?= htmlspecialchars($informacionPersonal['RFC']) ?></p>
        <?php endif; ?>

        <input type="submit" value="Confirmar Información" class="btn-1">
    </form>
</body>
</html>
