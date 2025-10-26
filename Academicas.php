<?php
session_start();
include 'Conexion.php';

// Verifica si el usuario está logueado
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: Login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Obtener información del usuario
$query = "SELECT u.nombre, u.tiposusuariosid, ip.nombres, ip.primerapellido, ip.segundoapellido, ip.email 
          FROM Usuarios u 
          LEFT JOIN InformacionPersonal ip ON u.ID_usuarios = ip.usuariosid 
          WHERE u.ID_usuarios = ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$usuario = mysqli_fetch_assoc($result);
mysqli_stmt_close($stmt);

// Obtener información académica
$queryAcademica = "SELECT iae.*, c.nombre as carrera_nombre, p.nombre as periodo_nombre 
                   FROM InformacionAcademica_estudiante iae 
                   LEFT JOIN Carrera c ON iae.carreraId = c.ID_carrera 
                   LEFT JOIN Periodo p ON iae.periodoid = p.ID_periodo 
                   WHERE iae.usuariosid = ?";
$stmtAcademica = mysqli_prepare($conn, $queryAcademica);
mysqli_stmt_bind_param($stmtAcademica, "i", $user_id);
mysqli_stmt_execute($stmtAcademica);
$resultAcademica = mysqli_stmt_get_result($stmtAcademica);
$infoAcademica = mysqli_fetch_assoc($resultAcademica);
mysqli_stmt_close($stmtAcademica);

// Obtener materias del estudiante (si tienes esta tabla)
$queryMaterias = "SELECT m.nombre, cm.semestre 
                  FROM Carrera_Materia cm 
                  LEFT JOIN Materia m ON cm.materiaid = m.ID_materia 
                  WHERE cm.carreraid = ? 
                  ORDER BY cm.semestre";

if (isset($infoAcademica['carreraId'])) {
    $stmtMaterias = mysqli_prepare($conn, $queryMaterias);
    mysqli_stmt_bind_param($stmtMaterias, "i", $infoAcademica['carreraId']);
    mysqli_stmt_execute($stmtMaterias);
    $resultMaterias = mysqli_stmt_get_result($stmtMaterias);
    $materias = [];
    while ($row = mysqli_fetch_assoc($resultMaterias)) {
        $materias[] = $row;
    }
    mysqli_stmt_close($stmtMaterias);
}

// Cerrar conexión al final
mysqli_close($conn);
?>

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
        }
        .logout-btn:hover {
            background-color: #c82333;
        }
    </style>
</head>
<body>

<div class="dashboard-container">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h1>Portal Académico</h1>
        <a href="Logout.php" class="logout-btn">Cerrar Sesión</a>
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
    <?php if ($infoAcademica): ?>
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

    <!-- Materias (si existen) -->
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