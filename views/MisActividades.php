<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mis Actividades</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <style>
        body {
            background-image: url('../Imagenes/bg.jpg');
            background-position: center;
            background-repeat: no-repeat;
            background-size: cover;
            background-attachment: fixed;
            min-height: 100vh;
            padding: 20px;
        }
        .container {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.3);
        }
        .actividad-card {
            border-left: 4px solid #667eea;
            margin-bottom: 15px;
            padding: 15px;
            background: #f8f9fa;
            border-radius: 8px;
        }
        .actividad-card.proxima {
            border-left-color: #ffc107;
            background: #fff3cd;
        }
        .actividad-card.vencida {
            border-left-color: #dc3545;
            background: #f8d7da;
        }
    </style>
</head>
<body>

<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>📝 Mis Actividades Académicas</h2>
        <div>
            <a href="../views/index.php" class="btn btn-secondary">← Volver</a>
        </div>
    </div>

    <?php if (isset($mensaje) && $mensaje): ?>
        <div class="alert alert-info alert-dismissible fade show">
            <?= htmlspecialchars($mensaje) ?>
            <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>
    <?php endif; ?>

    <button type="button" class="btn btn-primary mb-4" data-toggle="modal" data-target="#addModal">
        ➕ Agregar Nueva Actividad
    </button>

    <div class="row">
        <?php if (empty($actividades)): ?>
            <div class="col-12">
                <div class="alert alert-info text-center">
                    No tienes actividades registradas. ¡Agrega tu primera actividad!
                </div>
            </div>
        <?php else: ?>
            <?php 
            $hoy = date('Y-m-d');
            foreach ($actividades as $actividad): 
                $fecha_actividad = date('Y-m-d', strtotime($actividad['fecha']));
                $dias_restantes = (strtotime($fecha_actividad) - strtotime($hoy)) / (60 * 60 * 24);
                
                $clase = '';
                if ($dias_restantes < 0) {
                    $clase = 'vencida';
                } elseif ($dias_restantes <= 2) {
                    $clase = 'proxima';
                }
            ?>
            <div class="col-md-6">
                <div class="actividad-card <?= $clase ?>">
                    <h5>📚 Actividad</h5>
                    <p><strong>Materia:</strong> <?= htmlspecialchars($actividad['materia_nombre'] ?? 'Sin materia') ?></p>
                    <p><strong>Descripción:</strong> <?= htmlspecialchars($actividad['descripcion']) ?></p>
                    <p><strong>Fecha:</strong> <?= date('d/m/Y', strtotime($actividad['fecha'])) ?></p>
                    
                    <?php if ($dias_restantes < 0): ?>
                        <span class="badge badge-danger">⚠️ Vencida</span>
                    <?php elseif ($dias_restantes == 0): ?>
                        <span class="badge badge-warning">🔔 ¡Hoy!</span>
                    <?php elseif ($dias_restantes == 1): ?>
                        <span class="badge badge-warning">⏰ Mañana</span>
                    <?php elseif ($dias_restantes <= 2): ?>
                        <span class="badge badge-warning">⏰ En <?= $dias_restantes ?> días</span>
                    <?php endif; ?>
                    
                    <div class="mt-3">
                        <button class="btn btn-sm btn-info" onclick="editarActividad(<?= $actividad['ID_actividadesacademicas'] ?>, '<?= htmlspecialchars($actividad['descripcion']) ?>', <?= $actividad['materiaId'] ?? 0 ?>, '<?= $actividad['fecha'] ?>')">
                            ✏️ Editar
                        </button>
                        <form method="POST" action="../controllers/ActividadController.php?action=eliminar" style="display:inline;" onsubmit="return confirm('¿Eliminar esta actividad?');">
                            <input type="hidden" name="id_actividad" value="<?= $actividad['ID_actividadesacademicas'] ?>">
                            <button type="submit" class="btn btn-sm btn-danger">🗑️ Eliminar</button>
                        </form>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

<!-- Modal Agregar -->
<div class="modal fade" id="addModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Agregar Actividad</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <form method="POST" action="../controllers/ActividadController.php?action=crear">
                <div class="modal-body">
                    <div class="form-group">
                        <label>Materia:</label>
                        <select name="materiaId" class="form-control" required>
                            <option value="">Seleccionar...</option>
                            <?php foreach ($materias as $materia): ?>
                                <option value="<?= $materia['ID_materia'] ?>"><?= htmlspecialchars($materia['nombre']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Descripción:</label>
                        <textarea name="descripcion" class="form-control" rows="3" required placeholder="Ej: Entregar tarea de matemáticas"></textarea>
                    </div>
                    <div class="form-group">
                        <label>Fecha de entrega:</label>
                        <input type="date" name="fecha" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Editar -->
<div class="modal fade" id="editModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Editar Actividad</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <form method="POST" action="../controllers/ActividadController.php?action=actualizar">
                <input type="hidden" name="id_actividad" id="edit_id">
                <div class="modal-body">
                    <div class="form-group">
                        <label>Materia:</label>
                        <select name="materiaId" id="edit_materia" class="form-control" required>
                            <?php foreach ($materias as $materia): ?>
                                <option value="<?= $materia['ID_materia'] ?>"><?= htmlspecialchars($materia['nombre']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Descripción:</label>
                        <textarea name="descripcion" id="edit_descripcion" class="form-control" rows="3" required></textarea>
                    </div>
                    <div class="form-group">
                        <label>Fecha:</label>
                        <input type="date" name="fecha" id="edit_fecha" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Actualizar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function editarActividad(id, descripcion, materiaId, fecha) {
    $('#edit_id').val(id);
    $('#edit_descripcion').val(descripcion);
    $('#edit_materia').val(materiaId);
    $('#edit_fecha').val(fecha);
    $('#editModal').modal('show');
}
</script>

</body>
</html>