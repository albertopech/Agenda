<?php
session_start();
$isLoggedIn = isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true;
?>
<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="./Style/main.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <title>Mi Agenda</title>
</head>
<body>

<header class="container-fluid bg-primary d-flex justify-content-center">
    <p class="text-light mb-0 p-2 fs-6">Contactanos 1-(305)-725-1000</p>
</header>

<nav class="navbar navbar-expand-lg navbar-light p-3" id="menu">
    <div class="container">
        <a class="navbar-brand" href="#">
            <span class="fs-5 text-primary fw-bold">Mi Agenda</span>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo $isLoggedIn ? '../controllers/ActividadController.php?action=index' : '#'; ?>">Mis Actividades</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#equipo">Equipo</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#seccion-contacto">Contactos</a>
                </li>
            </ul>
            <form class="d-flex">
                <?php if ($isLoggedIn): ?>
                    <a href="../controllers/AcademicasController.php" class="button" style="margin-right: 10px;">Mi Portal</a>
                    <a href="../controllers/AuthController.php?action=logout" class="button">Cerrar Sesión</a>
                <?php else: ?>
                    <a href="../controllers/AuthController.php?action=login" class="button">Iniciar Sesión</a>
                <?php endif; ?>
            </form>
        </div>
    </div>
</nav>

<!-- SLIDER DE IMAGENES-->
<div id="carousel" class="carousel slide" data-bs-ride="carousel">
    <div class="carousel-inner">
        <div class="carousel-item active" data-bs-interval="3000">
            <img src="../Imagenes/slide1.jpg" class="d-block w-100" alt="">
        </div>
        <div class="carousel-item" data-bs-interval="3000">
            <img src="../Imagenes/slide2.jpg" class="d-block w-100" alt="...">
        </div>
        <div class="carousel-item" data-bs-interval="3000">
            <img src="../Imagenes/slide3.jpg" class="d-block w-100" alt="...">
        </div>
    </div>
    <button class="carousel-control-prev" type="button" data-bs-target="#carousel" data-bs-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Previous</span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#carousel" data-bs-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Next</span>
    </button>
</div>

<!-- INTRODUCCION DE SERVICIOS-->
<section class="d-flex flex-column justify-content-center align-items-center pt-5 text-center w-50 m-auto" id="intro">
    <h1 class="p-3 fs-2 border-top border-3">Mi Agenda única para todas tus actividades de <span class="text-primary">Materias escolares</span></h1>
    <p class="p-3 fs-4">
        <span class="text-primary">Mi Agenda</span> es la agenda donde te ayudamos a recordar tus actividades escolares.
    </p>
</section>

<!-- TIPOS DE SERVICIOS-->
<section class="w-100">
    <div class="row w-75 mx-auto" id="servicios-fila-1">
        <div class="col-lg-6 col-md-12 col-sm-12 d-flex justify-content-start my-5 icono-wrap">
            <img src="../Imagenes/desarrollo.png" alt="desarrollo" width="180" height="160">
            <div>
                <h3 class="fs-5 mt-4 px-4 pb-1">Mis actividades</h3>
                <p class="px-4">Administración de actividades: Tareas, Exámenes, Proyectos</p>
            </div>
        </div>

        <div class="col-lg-6 col-md-12 col-sm-12 d-flex justify-content-start my-5 icono-wrap">
            <img src="../Imagenes/concepto.png" alt="concepto" width="180" height="160">
            <div>
                <h3 class="fs-5 mt-4 px-4 pb-1 icono-wrap">Recordatorio</h3>
                <p class="px-4">Se te recordará un día antes de su entrega de fecha</p>
            </div>
        </div>
    </div>
    
    <div class="row w-75 mx-auto mb-5" id="servicios-fila-2">
        <div class="col-lg-6 col-md-12 col-sm-12 d-flex justify-content-start my-5 icono-wrap">
            <img src="../Imagenes/comunicaciones.png" alt="comunicaciones" width="180" height="160">
            <div>
                <h3 class="fs-5 mt-4 px-4 pb-1">Página</h3>
                <p class="px-4">Página optimizada para mejor experiencia para nuestros usuarios.</p>
            </div>
        </div>

        <div class="col-lg-6 col-md-12 col-sm-12 d-flex justify-content-start my-5 icono-wrap">
            <img src="../Imagenes/seo.png" alt="seo" width="180" height="160">
            <div>
                <h3 class="fs-5 mt-4 px-4 pb-1">SEO</h3>
                <p class="px-4">Analizamos la eficiencia y estamos mejorando día con día</p>
            </div>
        </div>
    </div>
</section>

<!-- SECCION ACERCA DE NOSOTROS-->
<section>
    <div class="container w-50 m-auto text-center" id="equipo">
        <h1 class="mb-5 fs-2">Equipo pequeño con <span class="text-primary">resultados Grandes</span>.</h1>
        <p class="fs-4">Siempre buscamos la manera adecuada para poder guardar tus actividades. Si te sientes listo, te esperamos para que utilices nuestro sitio web Mi Agenda.</p>
    </div>

    <div class="mt-5 text-center">
        <img id="img-equipo" src="../Imagenes/equipo.jpg" alt="equipo">
    </div>

    <div id="local" class="border-top border-2">
    <div class="mapa">
        <img src="../Imagenes/mapa.PNG" alt="Mapa de Chetumal" class="img-fluid">
    </div>
    <div>
        <div class="wrapper-local">
            <h2>Ubicado en Chetumal, QRoo</h2>
            <h2 class="text-primary mb-4" id="typewriter"></h2>
            <p class="fs-5 text-body">
                Seleccionamos Chetumal, Quintana Roo, como sede de nuestra agenda estudiantil para estar más cerca de nuestros alumnos del Instituto Tecnológico de México. Estamos ubicados en un punto estratégico de la ciudad, en el corazón de Chetumal, con acceso fácil a los mejores lugares para comer, comprar y a solo unos minutos de las hermosas playas. ¡Ven a visitarnos y descubre cómo nuestra agenda puede ayudarte en tu día a día estudiantil!
            </p>
            <section class="d-flex justify-content-start" id="numeros-local">
                <div>
                    <p class="text-primary fs-5">200</p>
                    <p>Días de Sol</p>
                </div>
                <div>
                    <p class="text-primary fs-5">100%</p>
                    <p>Aprobado</p>
                </div>
                <div>
                    <p class="text-primary fs-5">24 °C</p>
                    <p>Temperatura</p>
                </div>
            </section>
        </div>
    </div>
</div>
</section>

<!-- SECCION CONTACTOS-->
<section id="seccion-contacto" class="border-bottom border-secondary border-2">
    <div id="bg-contactos">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320">
            <path fill="#1b2a4e" fill-opacity="1" d="M0,32L120,42.7C240,53,480,75,720,74.7C960,75,1200,53,1320,42.7L1440,32L1440,320L1320,320C1200,320,960,320,720,320C480,320,240,320,120,320L0,320Z"></path>
        </svg>
    </div>

    <div class="container border-top border-primary" style="max-width: 500px" id="contenedor-formulario">
        <div class="text-center mb-4" id="titulo-formulario">
            <div><img src="../Imagenes/support.png" alt="" class="img-fluid ps-5"></div>
            <h2>Contáctanos</h2>
            <p class="fs-5">Estamos aquí para hacer realidad tus proyectos</p>
        </div>

        <form id="formulario-contacto">
            <div class="mb-3">
                <input type="email" class="form-control" name="email" placeholder="nombre@ejemplo.com">
            </div>
            <div class="mb-3">
                <input type="input" class="form-control" name="name" placeholder="Nombre">
            </div>
            <div class="mb-3">
                <input type="tel" class="form-control" name="phone" placeholder="Teléfono">
            </div>
            <div class="mb-3">
                <textarea class="form-control" name="message" rows="4"></textarea>
            </div>
            <div class="mb-3">
                <button type="submit" class="btn btn-primary w-100 fs-5">Enviar Mensaje</button>
            </div>
        </form>
    </div>

    <script>
    $(document).ready(function() {
        $('#formulario-contacto').submit(function(event) {
            event.preventDefault();
            var formData = new FormData(this);
            $.ajax({
                url: '../controllers/ContactoController.php',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    alert('¡Gracias por contactarnos! Tu mensaje ha sido enviado correctamente.');
                    window.location.href = 'index.php';
                },
                error: function() {
                    alert('Hubo un error al enviar el formulario.');
                }
            });
        });
    });
    </script>
</section>

<!--FOOTER-->
<footer class="w-100 d-flex align-items-center justify-content-center flex-wrap">
    <p class="fs-5 px-3 pt-3">Mi Agenda &copy; Todos Los Derechos Reservados 2024</p>
    <div id="iconos">
        <a href="#"><i class="bi bi-facebook"></i></a>
        <a href="#"><i class="bi bi-twitter"></i></a>
        <a href="#"><i class="bi bi-instagram"></i></a>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://unpkg.com/typewriter-effect@latest/dist/core.js"></script>
<script src="./main.js"></script>
</body>
</html>
