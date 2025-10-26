<?php
include 'Conexion.php';

// READ Carreras
$query = "SELECT * FROM Carrera";
$result = mysqli_query($conn, $query);
$carreras = [];
while ($row = mysqli_fetch_assoc($result)) {
    $carreras[] = $row;
}

// UPDATE Carrera
if (isset($_POST['update'])) {
    $ID_carrera = $_POST['ID_carrera'];
    $nombre = $_POST['nombre'];
    $perfil_carrera = $_POST['perfil_carrera'];
    $duracion = $_POST['duracion'];
    $descripcion = $_POST['descripcion'];

    $query = "UPDATE Carrera SET nombre = ?, perfil_carrera = ?, duracion = ?, descripcion = ? WHERE ID_carrera = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "ssssi", $nombre, $perfil_carrera, $duracion, $descripcion, $ID_carrera);
    
    if (mysqli_stmt_execute($stmt)) {
        header("Location: Carrera.php");
        exit();
    } else {
        die("Error: " . mysqli_error($conn));
    }
    mysqli_stmt_close($stmt);
}

// DELETE Carrera
if (isset($_POST['delete'])) {
    $ID_carrera = $_POST['ID_carrera'];

    // Iniciar transacción
    mysqli_begin_transaction($conn);

    try {
        // Actualizar los registros de estudiantes que tienen esta carrera asociada
        $queryUpdateEstudiantes = "UPDATE InformacionAcademica_estudiante SET carreraid = NULL WHERE carreraid = ?";
        $stmtUpdate = mysqli_prepare($conn, $queryUpdateEstudiantes);
        mysqli_stmt_bind_param($stmtUpdate, "i", $ID_carrera);
        mysqli_stmt_execute($stmtUpdate);
        mysqli_stmt_close($stmtUpdate);

        // Eliminar la carrera
        $queryDeleteCarrera = "DELETE FROM Carrera WHERE ID_carrera = ?";
        $stmtDelete = mysqli_prepare($conn, $queryDeleteCarrera);
        mysqli_stmt_bind_param($stmtDelete, "i", $ID_carrera);
        mysqli_stmt_execute($stmtDelete);
        mysqli_stmt_close($stmtDelete);

        // Confirmar transacción
        mysqli_commit($conn);
        header("Location: Carrera.php");
        exit();
    } catch (Exception $e) {
        // Revertir transacción en caso de error
        mysqli_rollback($conn);
        die("Error: " . $e->getMessage());
    }
}

// ADD Carrera
if (isset($_POST['add'])) {
    $nombre = $_POST['nombre'];
    $perfil_carrera = $_POST['perfil_carrera'];
    $duracion = $_POST['duracion'];
    $descripcion = $_POST['descripcion'];

    $query = "INSERT INTO Carrera (nombre, perfil_carrera, duracion, descripcion) VALUES (?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "ssss", $nombre, $perfil_carrera, $duracion, $descripcion);
    
    if (mysqli_stmt_execute($stmt)) {
        header("Location: Carrera.php");
        exit();
    } else {
        die("Error: " . mysqli_error($conn));
    }
    mysqli_stmt_close($stmt);
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
    <title>Gestión de Carreras</title>
</head>
<body>

<div class="container mt-4">
    <h2 class="mb-4">Gestión de Carreras</h2>
    <div class="mb-3">
        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addCarreraModal">Agregar Carrera</button>
        <a href="Admin.php" class="btn btn-secondary">Volver a Administración</a>
    </div>

    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Nombre</th>
                    <th>Perfil de Carrera</th>
                    <th>Duración</th>
                    <th>Descripción</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach($carreras as $carrera): ?>
                <form action="Carrera.php" method="post">
                    <tr>
                        <td><input type="hidden" name="ID_carrera" value="<?= $carrera['ID_carrera'] ?>"><?= $carrera['ID_carrera']?></td>
                        <td>
                            <input type="text" class="form-control" name="nombre" value="<?= htmlspecialchars($carrera['nombre']) ?>" required disabled>
                        </td>
                        <td>
                            <input type="text" class="form-control" name="perfil_carrera" value="<?= htmlspecialchars($carrera['perfil_carrera']) ?>" required disabled>
                        </td>
                        <td>
                            <input type="text" class="form-control" name="duracion" value="<?= htmlspecialchars($carrera['duracion']) ?>" required disabled>
                        </td>
                        <td>
                            <input type="text" class="form-control" name="descripcion" value="<?= htmlspecialchars($carrera['descripcion']) ?>" required disabled>
                        </td>
                        <td>
                            <button type="button" class="btn btn-sm btn-primary edit-button">Editar</button>
                            <div class="action-buttons d-none" style="min-width: 200px"> 
                                <input type="hidden" name="ID_carrera" value="<?= $carrera['ID_carrera'] ?>">
                                <button type="submit" name="update" class="btn btn-sm btn-success">Guardar</button>
                                <button type="submit" name="delete" class="btn btn-sm btn-danger" onclick="return confirm('¿Está seguro de que desea eliminar esta carrera?');">Eliminar</button>
                            </div>
                        </td>
                    </tr>
                </form>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <!-- Modal para añadir nueva carrera -->
    <div class="modal fade" id="addCarreraModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Añadir Nueva Carrera</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <form action="Carrera.php" method="POST">
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Nombre de la Carrera</label>
                            <input type="text" class="form-control" name="nombre" required>
                        </div>
                        <div class="form-group">
                            <label>Perfil de Carrera</label>
                            <input type="text" class="form-control" name="perfil_carrera" required>
                        </div>
                        <div class="form-group">
                            <label>Tipo de Carrera</label>
                            <div class="form-check">
                                <input type="radio" class="form-check-input" name="tipoCarrera" value="escolarizada" onclick="mostrarDuracion()">
                                <label class="form-check-label">Escolarizada</label>
                            </div>
                            <div class="form-check">
                                <input type="radio" class="form-check-input" name="tipoCarrera" value="mixta" onclick="mostrarDuracion()">
                                <label class="form-check-label">Mixta</label>
                            </div>
                        </div>
                        <div class="form-group" id="seccionDuracion" style="display:none;">
                            <label>Duración</label>
                            <select class="form-control" id="duracionCarrera" name="duracion" required>
                                <?php for ($i = 7; $i <= 12; $i++): ?>
                                    <option value="<?= $i ?>" <?= ($i == 9) ? 'selected' : '' ?>><?= $i ?></option>
                                <?php endfor; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Descripción</label>
                            <input type="text" class="form-control" name="descripcion" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                        <button type="submit" name="add" class="btn btn-primary">Añadir Carrera</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function mostrarDuracion() {
    var seccionDuracion = document.getElementById("seccionDuracion");
    var duracionCarrera = document.getElementById("duracionCarrera");
    
    if (document.querySelector('input[value="escolarizada"]').checked) {
        duracionCarrera.innerHTML = "";
        for (var i = 7; i <= 12; i++) {
            duracionCarrera.innerHTML += "<option value='" + i + "'" + (i == 9 ? " selected" : "") + ">" + i + "</option>";
        }
    } else {
        duracionCarrera.innerHTML = "";
        for (var i = 12; i <= 18; i++) {
            duracionCarrera.innerHTML += "<option value='" + i + "'" + (i == 14 ? " selected" : "") + ">" + i + "</option>";
        }
    }
    seccionDuracion.style.display = "block";
}

$(document).ready(function() {
    $('.edit-button').click(function() {
        $(this).closest('tr').find('input, select').removeAttr('disabled');
        $(this).hide();
        $(this).closest('tr').find('.action-buttons').removeClass('d-none');
    });
});
</script>

</body>
</html>