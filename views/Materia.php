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
            <?php if (isset($materias)): ?>
            <?php foreach($materias as $materia): 
                $es_reticula_value = "generica";
                if (isset($carrera_materia)) {
                    foreach($carrera_materia as $cm) {
                        if ($cm['materiaid'] == $materia['ID_materia'] && $cm['es_reticula'] == 1) {
                            $es_reticula_value = "reticula";
                            break;
                        }
                    }
                }
            ?>
                <tr data-es-reticula="<?= $es_reticula_value ?>">
                    <form action="../controllers/MateriaController.php" method="post">
                        <td><input type="hidden" name="ID_materia" value="<?= $materia['ID_materia'] ?>"><?= $materia['ID_materia'] ?></td>
                        <td>
                            <input type="text" class="form-control" name="nombre" value="<?= htmlspecialchars($materia['nombre']) ?>" required disabled>
                        </td>
                        <td>
                            <select name="periodoId" class="form-control" disabled>
                                <?php if (isset($periodos)): ?>
                                <?php foreach($periodos as $periodo): ?>
                                    <option value="<?= $periodo['ID_periodo'] ?>" <?= $materia['periodoId'] == $periodo['ID_periodo'] ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($periodo['nombre']) ?>
                                    </option>
                                <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                        </td>
                        <td>
                            <select name="carreraId" class="form-control" disabled>
                                <?php if (isset($carreras)): ?>
                                <?php foreach($carreras as $carrera): ?>
                                    <option value="<?= $carrera['ID_carrera'] ?>" <?= $materia['carreraId'] == $carrera['ID_carrera'] ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($carrera['nombre']) ?>
                                    </option>
                                <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                        </td>
                        <td>
                            <button type="button" class="btn btn-sm btn-primary edit-button">Editar</button>
                            <div class="action-buttons d-none" style="min-width: 200px"> 
                                <button type="submit" name="update" formaction="../controllers/MateriaController.php?action=actualizar" class="btn btn-sm btn-success">Guardar</button>
                                <button type="submit" name="delete" formaction="../controllers/MateriaController.php?action=eliminar" class="btn btn-sm btn-danger" onclick="return confirm('¿Está seguro de que desea eliminar esta materia?');">Eliminar</button>
                            </div>
                        </td>
                    </form>
                </tr>
            <?php endforeach; ?>
            <?php endif; ?>
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
                <form action="../controllers/MateriaController.php?action=crear" method="POST">
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Nombre de la Materia</label>
                            <input type="text" class="form-control" name="nombre" required>
                        </div>
                        <div class="form-group">
                            <label>Período</label>
                            <select name="periodoId" class="form-control" required>
                                <?php if (isset($periodos)): ?>
                                <?php foreach($periodos as $periodo): ?>
                                    <option value="<?= $periodo['ID_periodo'] ?>"><?= htmlspecialchars($periodo['nombre']) ?></option>
                                <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Carrera</label>
                            <select class="form-control" name="carreraId" required>
                                <?php if (isset($carreras)): ?>
                                <?php foreach($carreras as $carrera): ?>
                                    <option value="<?= $carrera['ID_carrera'] ?>"><?= htmlspecialchars($carrera['nombre']) ?></option>
                                <?php endforeach; ?>
                                <?php endif; ?>
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