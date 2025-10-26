<?php
session_start();
include 'Conexion.php';

// Verificar si el usuario está logueado
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: Login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$mensaje = "";

// AGREGAR nueva actividad
if (isset($_POST['add'])) {
    $materiaId = $_POST['materiaId'];
    $tipoActividadId = $_POST['tipoActividadId'];
    $descripcion = $_POST['descripcion'];
    $fecha = $_POST['fecha'];
    
    $query = "INSERT INTO ActividadesAcademicas (usuariosid, materiaId, tipoactividadid, descripcion, fecha) VALUES (?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "iiiss", $user_id, $materiaId, $tipoActividadId, $descripcion, $fecha);
    
    if (mysqli_stmt_execute($stmt)) {
        $mensaje = "✅ Actividad agregada con éxito";
    } else {
        $mensaje = "❌ Error al agregar actividad";
    }
    mysqli_stmt_close($stmt);
}

// ELIMINAR actividad
if (isset($_POST['delete'])) {
    $id_actividad = $_POST['id_actividad'];
    
    $query = "DELETE FROM ActividadesAcademicas WHERE ID_actividadesacademicas = ? AND usuariosid = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "ii", $id_actividad, $user_id);
    
    if (mysqli_stmt_execute($stmt)) {
        $mensaje = "✅ Actividad eliminada";
    }
    mysqli_stmt_close($stmt);
}

// EDITAR actividad
if (isset($_POST['update'])) {
    $id_actividad = $_POST['id_actividad'];
    $materiaId = $_POST['materiaId'];
    $tipoActividadId = $_POST['tipoActividadId'];
    $descripcion = $_POST['descripcion'];
    $fecha = $_POST['fecha'];
    
    $query = "UPDATE ActividadesAcademicas SET materiaId = ?, tipoactividadid = ?, descripcion = ?, fecha = ? WHERE ID_actividadesacademicas = ? AND usuariosid = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "iissii", $materiaId, $tipoActividadId, $descripcion, $fecha, $id_actividad, $user_id);
    
    if (mysqli_stmt_execute($stmt)) {
        $mensaje = "✅ Actividad actualizada";
    }
    mysqli_stmt_close($stmt);
}

// Obtener las actividades del usuario
$query = "SELECT a.*, m.nombre as materia_nombre, t.nombre as tipo_nombre 
          FROM ActividadesAcademicas a
          LEFT JOIN Materia m ON a.materiaId = m.ID_materia
          LEFT JOIN Tipos_Actividades t ON a.tipoactividadid = t.ID_tipos_actividades
          WHERE a.usuariosid = ?
          ORDER BY a.fecha ASC";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$actividades = [];
while ($row = mysqli_fetch_assoc($result)) {
    $actividades[] = $row;
}
mysqli_stmt_close($stmt);

// Obtener materias para el select
$queryMaterias = "SELECT * FROM Materia";
$resultMaterias = mysqli_query($conn, $queryMaterias);
$materias = [];
while ($row = mysqli_fetch_assoc($resultMaterias)) {
    $materias[] = $row;
}

// Obtener tipos de actividades
$queryTipos = "SELECT * FROM Tipos_Actividades";
$resultTipos = mysqli_query($conn, $queryTipos);
$tipos = [];
while ($row = mysqli_fetch_assoc($resultTipos)) {
    $tipos[] = $row;
}

mysqli_close($conn);
?>

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
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
        }
        .container {
            background: white;
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
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
        .btn-volver {
            margin-bottom: 20px;
        }
    </style>
</head>
<body>

<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>📝 Mis Actividades Académicas</h2>
        <div>
            <a href="Academicas.php" class="btn btn-secondary">← Volver</a>
            <a href="Logout.php" class="btn btn-danger">Cerrar Sesión</a>
        </div>
    </div>

    <?php if ($mensaje): ?>
        <div class="alert alert-info"><?= $mensaje ?></div>
    <?php endif; ?>

    <!-- Botón para agregar actividad -->
    <button type="button" class="btn btn-primary mb-4" data-toggle="modal" data-target="#addModal">
        ➕ Agregar Nueva Actividad
    </button>

    <!-- Lista de actividades -->
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
                    <h5><?= htmlspecialchars($actividad['tipo_nombre'] ?? 'Actividad') ?></h5>
                    <p><strong>Materia:</strong> <?= htmlspecialchars($actividad['materia_nombre']) ?></p>
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
                        <button class="btn btn-sm btn-info" onclick="editarActividad(<?= $actividad['ID_actividadesacademicas'] ?>, '<?= htmlspecialchars($actividad['descripcion']) ?>', <?= $actividad['materiaId'] ?>, <?= $actividad['tipoactividadid'] ?>, '<?= $actividad['fecha'] ?>')">
                            ✏️ Editar
                        </button>
                        <form method="POST" style="display:inline;" onsubmit="return confirm('¿Eliminar esta actividad?');">
                            <input type="hidden" name="id_actividad" value="<?= $actividad['ID_actividadesacademicas'] ?>">
                            <button type="submit" name="delete" class="btn btn-sm btn-danger">🗑️ Eliminar</button>
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
            <form method="POST">
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
                        <label>Tipo de Actividad:</label>
                        <select name="tipoActividadId" class="form-control" required>
                            <option value="">Seleccionar...</option>
                            <?php foreach ($tipos as $tipo): ?>
                                <option value="<?= $tipo['ID_tipos_actividades'] ?>"><?= htmlspecialchars($tipo['nombre']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Descripción:</label>
                        <textarea name="descripcion" class="form-control" rows="3" required></textarea>
                    </div>
                    <div class="form-group">
                        <label>Fecha de entrega:</label>
                        <input type="date" name="fecha" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="submit" name="add" class="btn btn-primary">Guardar</button>
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
            <form method="POST">
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
                        <label>Tipo:</label>
                        <select name="tipoActividadId" id="edit_tipo" class="form-control" required>
                            <?php foreach ($tipos as $tipo): ?>
                                <option value="<?= $tipo['ID_tipos_actividades'] ?>"><?= htmlspecialchars($tipo['nombre']) ?></option>
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
                    <button type="submit" name="update" class="btn btn-primary">Actualizar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function editarActividad(id, descripcion, materiaId, tipoId, fecha) {
    $('#edit_id').val(id);
    $('#edit_descripcion').val(descripcion);
    $('#edit_materia').val(materiaId);
    $('#edit_tipo').val(tipoId);
    $('#edit_fecha').val(fecha);
    $('#editModal').modal('show');
}
</script>

</body>
</html>