<?php
session_start();
include 'Conexion.php';

$mensaje = ""; // Inicializar la variable $mensaje

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $periodoId = isset($_POST["periodoId"]) ? $_POST["periodoId"] : '';
    $carreraId = isset($_POST["carreraId"]) ? $_POST["carreraId"] : '';
    $numcontrol = isset($_POST["numcontrol"]) ? $_POST["numcontrol"] : '';
    $semestre = isset($_POST["semestre"]) ? $_POST["semestre"] : '';
    $promedio = isset($_POST["promedio"]) ? $_POST["promedio"] : '';

    if (empty($periodoId) || empty($carreraId) || empty($numcontrol) || empty($semestre) || empty($promedio)) {
        $mensaje = "Por favor, complete todos los campos obligatorios.";
    } elseif (!is_numeric($semestre) || $semestre < 1 || $semestre > 12) {
        $mensaje = "El semestre debe ser un número válido entre 1 y 12.";
    } elseif (!preg_match('/^\d{2}\.\d$/', $promedio)) {
        $mensaje = "El promedio debe tener el formato correcto, por ejemplo, 90.0.";
    } elseif (!preg_match('/^\d{8}$/', $numcontrol)) {
        $mensaje = "El número de control debe tener 8 dígitos.";
    } else {
        // Verificar que existan todos los datos necesarios en la sesión
        if (!isset($_SESSION['registro_usuario']) || !isset($_SESSION['informacion_personal']) || !isset($_SESSION['informacion_contacto'])) {
            $mensaje = "Error: Datos de sesión incompletos. Por favor, inicie el registro nuevamente.";
        } else {
            // Iniciar transacción
            mysqli_begin_transaction($conn);
            
            try {
                // Insertar en la tabla Usuarios
                $usuario = $_SESSION['registro_usuario'];
                $queryUsuario = "INSERT INTO Usuarios (nombre, contrasenas, tiposusuariosid) VALUES (?, ?, ?)";
                $stmtUsuario = mysqli_prepare($conn, $queryUsuario);
                mysqli_stmt_bind_param($stmtUsuario, "ssi", $usuario['nombre'], $usuario['contrasena'], $usuario['tiposusuarioid']);
                
                if (!mysqli_stmt_execute($stmtUsuario)) {
                    throw new Exception("Error al crear usuario: " . mysqli_error($conn));
                }
                
                // Obtener el ID del usuario recién insertado
                $usuariosid = mysqli_insert_id($conn);
                mysqli_stmt_close($stmtUsuario);

                // Insertar en la tabla InformacionPersonal
                $personal = $_SESSION['informacion_personal'];
                $queryPersonal = "INSERT INTO InformacionPersonal (usuariosid, nombres, primerapellido, segundoapellido, fecha_nacimiento, telefono, email, RFC) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
                $stmtPersonal = mysqli_prepare($conn, $queryPersonal);
                mysqli_stmt_bind_param($stmtPersonal, "isssssss", $usuariosid, $personal['nombres'], $personal['primerapellido'], $personal['segundoapellido'], $personal['fecha_nacimiento'], $personal['telefono'], $personal['email'], $personal['RFC']);
                
                if (!mysqli_stmt_execute($stmtPersonal)) {
                    throw new Exception("Error al guardar información personal: " . mysqli_error($conn));
                }
                mysqli_stmt_close($stmtPersonal);

                // Insertar en la tabla InformacionContacto
                $contacto = $_SESSION['informacion_contacto'];
                $queryContacto = "INSERT INTO InformacionContacto (usuariosid, codigo_postal, municipio, estado, ciudad, colonia, calle_principal, primer_cruzamiento, segundo_cruzamiento, referencias, numero_exterior, numero_interior) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                $stmtContacto = mysqli_prepare($conn, $queryContacto);
                mysqli_stmt_bind_param($stmtContacto, "isssssssssss", $usuariosid, $contacto['codigo_postal'], $contacto['municipio'], $contacto['estado'], $contacto['ciudad'], $contacto['colonia'], $contacto['calle_principal'], $contacto['primer_cruzamiento'], $contacto['segundo_cruzamiento'], $contacto['referencias'], $contacto['numero_exterior'], $contacto['numero_interior']);
                
                if (!mysqli_stmt_execute($stmtContacto)) {
                    throw new Exception("Error al guardar información de contacto: " . mysqli_error($conn));
                }
                mysqli_stmt_close($stmtContacto);

                // Insertar en la tabla InformacionAcademica_estudiante
                $queryAcademica = "INSERT INTO InformacionAcademica_estudiante (usuariosid, periodoid, carreraId, numcontrol, semestre, promedio) VALUES (?, ?, ?, ?, ?, ?)";
                $stmtAcademica = mysqli_prepare($conn, $queryAcademica);
                mysqli_stmt_bind_param($stmtAcademica, "iiisid", $usuariosid, $periodoId, $carreraId, $numcontrol, $semestre, $promedio);
                
                if (!mysqli_stmt_execute($stmtAcademica)) {
                    throw new Exception("Error al guardar información académica: " . mysqli_error($conn));
                }
                mysqli_stmt_close($stmtAcademica);

                // Confirmar transacción
                mysqli_commit($conn);

                // Limpiar las sesiones
                unset($_SESSION['registro_usuario']);
                unset($_SESSION['informacion_personal']);
                unset($_SESSION['informacion_contacto']);

                // Redirigir al usuario a la página de inicio de sesión
                header("Location: Login.php?registro=exitoso");
                exit();
                
            } catch (Exception $e) {
                // Revertir transacción en caso de error
                mysqli_rollback($conn);
                $mensaje = "Error en el registro: " . $e->getMessage();
            }
        }
    }
}

// Obtener las opciones para los desplegables
$query = "SELECT * FROM Periodo";
$result = mysqli_query($conn, $query);
$periodos = [];
while($row = mysqli_fetch_assoc($result)) {
    $periodos[] = $row;
}

$query = "SELECT * FROM Carrera";
$result = mysqli_query($conn, $query);
$carreras = [];
while($row = mysqli_fetch_assoc($result)) {
    $carreras[] = $row;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Información Académica</title>
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

        // JavaScript para restringir el input del número de control
        document.addEventListener('DOMContentLoaded', function() {
            var numcontrolInput = document.getElementById('numcontrol');
            if (numcontrolInput) {
                numcontrolInput.addEventListener('input', function() {
                    var value = this.value.replace(/\D/g, '');
                    this.value = value.substring(0, 8);
                });
            }
        });
    </script>
</head>

<body>
<div class="container" style="display: flex; align-items: center; justify-content: center; height: 100vh; transform: scale(1.1);">
    <form action="InformacionAcademica_estudiante.php" method="post" style="display: flex; flex-direction: column; width: 400px; box-shadow: 0 0 20px rgba(0, 0, 0, 0.2); border-radius: 15px; background-color: #EEEEEE; padding: 35px;">
        <h2>Información Académica</h2>
        <?php
        if (!empty($mensaje)) {
            echo "<div class='alert' style='background: #f8d7da; color: #721c24; padding: 10px; border-radius: 5px; margin-bottom: 15px;'>" . htmlspecialchars($mensaje) . "</div>";
        }
        ?>

        <div class="form-group">
            <label for="periodoId"><p>Periodo:</p></label>
            <select name="periodoId" id="periodoId" style="padding: 8px; border-radius: 4px; margin-bottom: 15px;">
                <?php foreach ($periodos as $periodo): ?>
                    <option value="<?= htmlspecialchars($periodo['ID_periodo']) ?>"><?= htmlspecialchars($periodo['nombre']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group">
            <label for="carreraId"><p>Carrera:</p></label>
            <select name="carreraId" id="carreraId" style="padding: 8px; border-radius: 4px; margin-bottom: 15px;">
                <?php foreach ($carreras as $carrera): ?>
                    <option value="<?= htmlspecialchars($carrera['ID_carrera']) ?>"><?= htmlspecialchars($carrera['nombre']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group">
            <label for="numcontrol"><p>Número de Control:</p></label>
            <input type="text" name="numcontrol" id="numcontrol" style="padding: 8px; border-radius: 4px; margin-bottom: 15px;" pattern="\d{8}" title="Debe tener exactamente 8 dígitos" required>
        </div>

        <div class="form-group">
            <label for="semestre"><p>Semestre:</p></label>
            <select name="semestre" id="semestre" style="padding: 8px; border-radius: 4px; margin-bottom: 15px;">
                <?php for ($i = 1; $i <= 12; $i++): ?>
                    <option value="<?= $i ?>"><?= $i ?></option>
                <?php endfor; ?>
            </select>
        </div>

        <div class="form-group">
            <label for="promedio"><p>Promedio:</p></label>
            <input type="text" name="promedio" id="promedio" style="padding: 8px; border-radius: 4px; margin-bottom: 15px;" pattern="^\d{2}\.\d$" title="2 dígitos y 1 decimal, por ejemplo, 90.0" required>
            <small class="form-text"><p>Ejemplo válido: 90.0</p></small>
        </div>

        <button type="submit" class="button-custom">Completar Registro</button>
    </form>
</div>

</body>
</html>