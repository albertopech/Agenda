<?php
include 'Conexion.php';

// READ Materia
$query = "SELECT * FROM Materia";
$result = mysqli_query($conn, $query);
$materias = [];
while ($row = mysqli_fetch_assoc($result)) {
    $materias[] = $row;
}

// READ Periodo
$query = "SELECT * FROM Periodo";
$result = mysqli_query($conn, $query);
$periodos = [];
while($row = mysqli_fetch_assoc($result)) {
    $periodos[] = $row;
}

// READ carreras
$query = "SELECT * FROM Carrera";
$result = mysqli_query($conn, $query);
$carreras = [];
while ($row = mysqli_fetch_assoc($result)) {
    $carreras[] = $row;
}

// READ carrera_materia
$query = "SELECT * FROM Carrera_Materia";
$result = mysqli_query($conn, $query);
$carrera_materia = [];
while ($row = mysqli_fetch_assoc($result)) {
    $carrera_materia[] = $row;
}

// UPDATE
if (isset($_POST['update'])) {
    $ID_materia = $_POST['ID_materia'];
    $periodoId = $_POST["periodoId"];
    $carreraId = $_POST["carreraId"];
    $nombre = $_POST['nombre'];

    // Iniciar transacción
    mysqli_begin_transaction($conn);

    try {
        // Actualizar la tabla Materia
        $queryMateria = "UPDATE Materia SET periodoId = ?, carreraId = ?, nombre = ? WHERE ID_materia = ?";
        $stmtMateria = mysqli_prepare($conn, $queryMateria);
        mysqli_stmt_bind_param($stmtMateria, "iisi", $periodoId, $carreraId, $nombre, $ID_materia);
        mysqli_stmt_execute($stmtMateria);
        mysqli_stmt_close($stmtMateria);

        // Actualizar la tabla carrera_materia
        $queryCarreraMateria = "UPDATE Carrera_Materia SET carreraid = ? WHERE materiaid = ?";
        $stmtCarreraMateria = mysqli_prepare($conn, $queryCarreraMateria);
        mysqli_stmt_bind_param($stmtCarreraMateria, "ii", $carreraId, $ID_materia);
        mysqli_stmt_execute($stmtCarreraMateria);
        mysqli_stmt_close($stmtCarreraMateria);

        // Confirmar transacción
        mysqli_commit($conn);
        header("Location: Materia.php");
        exit();
    } catch (Exception $e) {
        mysqli_rollback($conn);
        die("Error: " . $e->getMessage());
    }
}

// DELETE
if (isset($_POST['delete'])) {
    $ID_materia = $_POST['ID_materia'];

    // Iniciar transacción
    mysqli_begin_transaction($conn);

    try {
        // Eliminar registros dependientes en ActividadesAcademicas
        $queryActividades = "DELETE FROM ActividadesAcademicas WHERE materiaID = ?";
        $stmtActividades = mysqli_prepare($conn, $queryActividades);
        mysqli_stmt_bind_param($stmtActividades, "i", $ID_materia);
        mysqli_stmt_execute($stmtActividades);
        mysqli_stmt_close($stmtActividades);

        // Eliminar registros dependientes en carrera_materia
        $queryCarreraMateria = "DELETE FROM Carrera_Materia WHERE materiaid = ?";
        $stmtCarreraMateria = mysqli_prepare($conn, $queryCarreraMateria);
        mysqli_stmt_bind_param($stmtCarreraMateria, "i", $ID_materia);
        mysqli_stmt_execute($stmtCarreraMateria);
        mysqli_stmt_close($stmtCarreraMateria);

        // Eliminar la materia
        $queryMateria = "DELETE FROM Materia WHERE ID_materia = ?";
        $stmtMateria = mysqli_prepare($conn, $queryMateria);
        mysqli_stmt_bind_param($stmtMateria, "i", $ID_materia);
        mysqli_stmt_execute($stmtMateria);
        mysqli_stmt_close($stmtMateria);

        // Confirmar transacción
        mysqli_commit($conn);
        header("Location: Materia.php");
        exit();
    } catch (Exception $e) {
        mysqli_rollback($conn);
        die("Error: " . $e->getMessage());
    }
}

// ADD
if (isset($_POST['add'])) {
    $nombre = $_POST['nombre'];
    $periodoId = isset($_POST["periodoId"]) && $_POST["periodoId"] != '' ? $_POST["periodoId"] : null;
    $carreraId = isset($_POST["carreraId"]) && $_POST["carreraId"] != '' ? $_POST["carreraId"] : null;
    $es_reticula = isset($_POST["es_reticula"]) ? $_POST["es_reticula"] : null;

    // Iniciar transacción
    mysqli_begin_transaction($conn);

    try {
        // Insertar la materia
        $queryMateria = "INSERT INTO Materia (nombre, periodoId, carreraId) VALUES (?, ?, ?)";
        $stmtMateria = mysqli_prepare($conn, $queryMateria);
        mysqli_stmt_bind_param($stmtMateria, "sii", $nombre, $periodoId, $carreraId);
        mysqli_stmt_execute($stmtMateria);
        
        // Obtener el ID de la materia insertada
        $materiaId = mysqli_insert_id($conn);
        mysqli_stmt_close($stmtMateria);

        // Insertar en carrera_materia
        $queryCarreraMateria = "INSERT INTO Carrera_Materia (carreraid, materiaid, es_reticula) VALUES (?, ?, ?)";
        $stmtCarreraMateria = mysqli_prepare($conn, $queryCarreraMateria);
        mysqli_stmt_bind_param($stmtCarreraMateria, "iii", $carreraId, $materiaId, $es_reticula);
        mysqli_stmt_execute($stmtCarreraMateria);
        mysqli_stmt_close($stmtCarreraMateria);

        // Confirmar transacción
        mysqli_commit($conn);
        header("Location: Materia.php");
        exit();
    } catch (Exception $e) {
        mysqli_rollback($conn);
        die("Error: " . $e->getMessage());
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</head>
<body>

<div class="container mt-4">
    <h2 class="mb-4">Gestión de Materias</h2>
    <div class="mb-3">
        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addMateriaModal">Agregar Materia</button>
        <a href="Admin.php" class="btn btn-secondary">Volver a Administración</a>
    </div>
    
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div class="input-group">
            <input type="text" class="form-control" id="searchInput" placeholder="Buscar materia...">
            <div class="input-group-append">
                <button class="btn btn-outline-secondary" type="button" id="filterButton">
                    <img src="Imagenes/filter.png" alt="Filtro" style="width: 20px; height: 20px;">
                </button>
            </div>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Nombre</th>
                    <th>Período</th>
                    <th>Carrera</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach($materias as $materia): 
                $es_reticula_value = "generica";
                foreach($carrera_materia as $cm) {
                    if ($cm['materiaid'] == $materia['ID_materia'] && $cm['es_reticula'] == 1) {
                        $es_reticula_value = "reticula";
                        break;
                    }
                }
            ?>
                <tr data-es-reticula="<?= $es_reticula_value ?>">
                    <form action="Materia.php" method="post">
                        <td><input type="hidden" name="ID_materia" value="<?= $materia['ID_materia'] ?>"><?= $materia['ID_materia'] ?></td>
                        <td>
                            <input type="text" class="form-control" name="nombre" value="<?= htmlspecialchars($materia['nombre']) ?>" required disabled>
                        </td>
                        <td>
                            <select name="periodoId" class="form-control" disabled>
                                <?php foreach($periodos as $periodo): ?>
                                    <option value="<?= $periodo['ID_periodo'] ?>" <?= $materia['periodoId'] == $periodo['ID_periodo'] ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($periodo['nombre']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </td>
                        <td>
                            <select name="carreraId" class="form-control" disabled>
                                <?php foreach($carreras as $carrera): ?>
                                    <option value="<?= $carrera['ID_carrera'] ?>" <?= $materia['carreraId'] == $carrera['ID_carrera'] ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($carrera['nombre']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </td>
                        <td>
                            <button type="button" class="btn btn-sm btn-primary edit-button">Editar</button>
                            <div class="action-buttons d-none" style="min-width: 200px"> 
                                <button type="submit" name="update" class="btn btn-sm btn-success">Guardar</button>
                                <button type="submit" name="delete" class="btn btn-sm btn-danger" onclick="return confirm('¿Está seguro de que desea eliminar esta materia?');">Eliminar</button>
                            </div>
                        </td>
                    </form>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <!-- Modal para añadir nueva materia -->
    <div class="modal fade" id="addMateriaModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Añadir Nueva Materia</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <form action="Materia.php" method="POST">
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Nombre de la Materia</label>
                            <input type="text" class="form-control" name="nombre" required>
                        </div>
                        <div class="form-group">
                            <label>Período</label>
                            <select name="periodoId" class="form-control" required>
                                <?php foreach($periodos as $periodo): ?>
                                    <option value="<?= $periodo['ID_periodo'] ?>"><?= htmlspecialchars($periodo['nombre']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Carrera</label>
                            <select class="form-control" name="carreraId" required>
                                <?php foreach($carreras as $carrera): ?>
                                    <option value="<?= $carrera['ID_carrera'] ?>"><?= htmlspecialchars($carrera['nombre']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Tipo de Materia</label>
                            <div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="es_reticula" value="1" required>
                                    <label class="form-check-label">Reticula</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="es_reticula" value="0" required>
                                    <label class="form-check-label">Generica</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                        <button type="submit" name="add" class="btn btn-primary">Añadir Materia</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal de filtros -->
    <div class="modal fade" id="filterModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Filtros de Búsqueda</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Selecciona tus filtros aquí:</p>
                    <div class="form-group">
                        <label>Tipo de Filtro:</label>
                        <select class="form-control" id="filterType">
                            <option value="all">Todos</option>
                            <option value="generica">Genérica</option>
                            <option value="reticula">Reticula</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    <button type="button" class="btn btn-primary" onclick="aplicarFiltro()">Aplicar Filtros</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('searchInput').addEventListener('keyup', function(event) {
    var searchQuery = event.target.value.toLowerCase();
    var allRows = document.querySelectorAll('.table tbody tr');
    allRows.forEach(function(row) {
        var nombreMateria = row.querySelector('td:nth-child(2) input').value.toLowerCase();
        row.style.display = nombreMateria.includes(searchQuery) ? '' : 'none';
    });
});

function aplicarFiltro() {
    var selectedOption = document.getElementById("filterType").value;
    var searchQuery = document.getElementById('searchInput').value.toLowerCase();
    var tableRows = document.querySelectorAll('.table tbody tr');
    tableRows.forEach(function(row) {
        var nombreMateria = row.querySelector('td:nth-child(2) input').value.toLowerCase();
        var esReticula = row.getAttribute("data-es-reticula");
        var showRow = (selectedOption === "all" || selectedOption === esReticula) && nombreMateria.includes(searchQuery);
        row.style.display = showRow ? "" : "none";
    });
    $('#filterModal').modal('hide');
}

$('#filterButton').click(function() {
    $('#filterModal').modal('show');
});

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