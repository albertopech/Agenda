<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Administración</title>
    <link href="Estilos.css" rel="stylesheet"/>
</head>
<body>

<!-- Barra de navegación con logo y botón -->
<header>
    <a class="logo" href="#"><img src="Imagenes/Tec.png" alt="Logo"></a>
    <nav>
        <script>
            function confirmLogout() {
                var response = confirm("¿Estás seguro de que deseas cerrar sesión?");
                if (response) {
                    window.location.href = 'Logout.php';
                }
            }
        </script>
        <a href="javascript:void(0);" onclick="confirmLogout()" class="button">Cerrar Sesión</a>
    </nav>
</header>

<div class="card-container">
    <div class="card" id="card7">
        <a href="Carrera.php" class="card-link">
            <button class="image-button7">
                <img src="Imagenes/carreras.jpg" alt="Carreras" class="card7">
            </button>
        </a>
        <span class="image-text7">Carreras</span>
    </div>

    <div class="card" id="card8">
        <a href="Materia.php" class="card-link">
            <button class="image-button8">
                <img src="Imagenes/Materias.jpg" alt="Materias" class="card8">
            </button>
        </a>
        <span class="image-text8">Materias</span>
    </div>
</div>

<footer>
    <div class="container-center">
        <div>
            <a class="logo" href="#"><img src="Imagenes/Tec.png" alt="Logo"></a>
            <p>&copy; 2022 – 2024</p>
            <p>Privacidad – Términos</p>
        </div>
        <div>
            <h3>Recursos</h3>
            <a href="Archivos/MapadelITCH2021.pdf">Mapa ITCH</a>
            <a href="#">Manual de Usuario</a>
        </div>
        <div>
            <h3>Redes</h3>
            <a href="https://github.com/xXValiSamaXx/Proyecto-WEB">Github</a>
        </div>
    </div>
</footer>

</body>
</html>