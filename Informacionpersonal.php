<?php
session_start();  // Inicia la sesión

include 'Conexion.php';

$mensajeError = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recoger los datos del formulario
    $nombres = isset($_POST['nombres']) ? htmlspecialchars($_POST['nombres']) : '';
    $primerapellido = isset($_POST['primerapellido']) ? htmlspecialchars($_POST['primerapellido']) : '';
    $segundoapellido = isset($_POST['segundoapellido']) ? htmlspecialchars($_POST['segundoapellido']) : '';
    $fecha_nacimiento = isset($_POST['fecha_nacimiento']) ? $_POST['fecha_nacimiento'] : '';
    $telefono = isset($_POST['telefono']) ? $_POST['telefono'] : '';
    $email = isset($_POST['email']) ? htmlspecialchars($_POST['email']) : '';
    $RFC = isset($_POST['RFC']) ? $_POST['RFC'] : null;

    // Validar la edad
    if (!empty($fecha_nacimiento)) {
        $fechaNacimiento = new DateTime($fecha_nacimiento);
        $fechaActual = new DateTime();
        $edad = $fechaActual->diff($fechaNacimiento)->y;
        
        if ($edad < 18) {
            $mensajeError = "Debes tener al menos 18 años para registrarte.";
        }
    } else {
        $mensajeError = "Por favor, ingrese su fecha de nacimiento.";
    }

    // Validar otros campos si la edad es adecuada
    if (empty($mensajeError)) {
        if (empty($nombres) || empty($primerapellido) || empty($telefono) || empty($email)) {
            $mensajeError = "Por favor, complete todos los campos obligatorios.";
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $mensajeError = "Ingrese una dirección de correo electrónico válida.";
        } 
    }

    // Almacenar datos en la sesión si no hay errores
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
    <title>Información Personal</title>
    <link href="Estilo2.css" rel="stylesheet"/>
    <script>
        window.addEventListener('popstate', function (event) {
            if (event.state && event.state.isBackNavigation) {
                if (confirm("Si regresa, se perderán todos los cambios.")) {
                    history.back();
                } else {
                    history.pushState({isBackNavigation: true}, "");
                }
            }
        });

        history.pushState({isBackNavigation: true}, "");
    </script>
</head>

<body>
<div class="container" style="display: flex; align-items: center; justify-content: center; height:100vh; transform: scale(0.9);">
    <form action="Informacionpersonal.php" method="post" style="display: flex; flex-direction: column; width: 400px; box-shadow: 0 0 20px rgba(0, 0, 0, 0.2); border-radius: 15px; background-color: #EEEEEE; padding: 35px;">
        <h2>Información Personal</h2>
        <?php
        if ($mensajeError != "") {
            echo "<div class='alert'>" . htmlspecialchars($mensajeError) . "</div>";
        }
        ?>

        <label for="nombres"><p>Nombres:</p></label>
        <input type="text" name="nombres" required>

        <label for="primerapellido"><p>Primer Apellido:</p></label>
        <input type="text" name="primerapellido" required>

        <label for="segundoapellido"><p>Segundo Apellido:</p></label>
        <input type="text" name="segundoapellido">

        <label for="fecha_nacimiento"><p>Fecha de Nacimiento:</p></label>
        <input type="date" name="fecha_nacimiento" required>

        <label for="telefono"><p>Teléfono:</p></label>
        <input type="text" id="telefono" name="telefono" required title="El número debe ser de 10 dígitos y contener solo números" maxlength="10" minlength="10">

        <label for="email"><p>Email:</p></label>
        <input type="email" name="email" required>

        <?php
        if (isset($_SESSION['registro_usuario']) && $_SESSION['registro_usuario']['tiposusuarioid'] != 1) {
            echo '<label for="RFC"><p>RFC:</p></label>';
            echo '<input type="text" name="RFC">';
        }
        ?>

        <input type="submit" value="Guardar" class="btn-1">
    </form>
</div>

<script>
    document.getElementById('telefono').addEventListener('input', function (e) {
        var x = e.target.value.replace(/\D/g, '');
        e.target.value = x;
    });
</script>
</body>
</html>