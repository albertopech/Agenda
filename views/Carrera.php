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
            <?php if (isset($carreras)): ?>
            <?php foreach($carreras as $carrera): ?>
                <form action="../controllers/CarreraController.php" method="post">
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
                                <button type="submit" name="update" formaction="../controllers/CarreraController.php?action=actualizar" class="btn btn-sm btn-success">Guardar</button>
                                <button type="submit" name="delete" formaction="../controllers/CarreraController.php?action=eliminar" class="btn btn-sm btn-danger" onclick="return confirm('¿Está seguro de que desea eliminar esta carrera?');">Eliminar</button>
                            </div>
                        </td>
                    </tr>
                </form>
            <?php endforeach; ?>
            <?php endif; ?>
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
                <form action="../controllers/CarreraController.php?action=crear" method="POST">
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