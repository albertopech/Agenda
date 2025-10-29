<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portal Académico</title>
    <link href="Estilo2.css" rel="stylesheet"/>
    <style>
        .dashboard-container {
            max-width: 1200px;
            margin: 20px auto;
            padding: 20px;
        }
        .info-card {
            background: white;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .info-card h3 {
            margin-top: 0;
            color: #333;
        }
        .info-row {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            border-bottom: 1px solid #eee;
        }
        .info-label {
            font-weight: bold;
            color: #666;
        }
        .info-value {
            color: #333;
        }
        .logout-btn {
            background-color: #dc3545;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            margin-left: 10px;
        }
        .logout-btn:hover {
            opacity: 0.9;
        }
        .actividades-btn {
            background-color: #28a745;
        }
        .actividades-btn:hover {
            background-color: #218838;
        }
    </style>
</head>
<body>

<div class="dashboard-container">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h1>Portal Académico</h1>
        <div>
            <a href="../controllers/ActividadController.php?action=index" class="logout-btn actividades-btn">📝 Mis Actividades</a>
            <a href="../views/index.php" class="logout-btn" style="background-color: #007bff;">← Volver al Inicio</a>
        </div>
    </div>

    <!-- Información Personal -->
    <div class="info-card">
        <h3>Información Personal</h3>
        <div class="info-row">
            <span class="info-label">Nombre Completo:</span>
            <span class="info-value">
                <?= htmlspecialchars($usuario['nombres'] ?? '') ?> 
                <?= htmlspecialchars($usuario['primerapellido'] ?? '') ?> 
                <?= htmlspecialchars($usuario['segundoapellido'] ?? '') ?>
            </span>
        </div>
        <div class="info-row">
            <span class="info-label">Usuario:</span>
            <span class="info-value"><?= htmlspecialchars($usuario['nombre']) ?></span>
        </div>
        <div class="info-row">
            <span class="info-label">Email:</span>
            <span class="info-value"><?= htmlspecialchars($usuario['email'] ?? 'No registrado') ?></span>
        </div>
    </div>

    <!-- Información Académica -->
    <?php if (isset($infoAcademica) && $infoAcademica): ?>
    <div class="info-card">
        <h3>Información Académica</h3>
        <div class="info-row">
            <span class="info-label">Carrera:</span>
            <span class="info-value"><?= htmlspecialchars($infoAcademica['carrera_nombre']) ?></span>
        </div>
        <div class="info-row">
            <span class="info-label">Número de Control:</span>
            <span class="info-value"><?= htmlspecialchars($infoAcademica['numcontrol']) ?></span>
        </div>
        <div class="info-row">
            <span class="info-label">Semestre:</span>
            <span class="info-value"><?= htmlspecialchars($infoAcademica['semestre']) ?></span>
        </div>
        <div class="info-row">
            <span class="info-label">Promedio:</span>
            <span class="info-value"><?= htmlspecialchars($infoAcademica['promedio']) ?></span>
        </div>
        <div class="info-row">
            <span class="info-label">Periodo:</span>
            <span class="info-value"><?= htmlspecialchars($infoAcademica['periodo_nombre']) ?></span>
        </div>
    </div>
    <?php endif; ?>

    <!-- Materias -->
    <?php if (!empty($materias)): ?>
    <div class="info-card">
        <h3>Materias de la Carrera</h3>
        <?php 
        $semestreActual = null;
        foreach ($materias as $materia): 
            if ($semestreActual !== $materia['semestre']):
                if ($semestreActual !== null) echo "</ul>";
                $semestreActual = $materia['semestre'];
                echo "<h4>Semestre {$semestreActual}</h4><ul>";
            endif;
        ?>
            <li><?= htmlspecialchars($materia['nombre']) ?></li>
        <?php endforeach; ?>
        </ul>
    </div>
    <?php endif; ?>

</div>

</body>
</html>