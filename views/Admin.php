<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Administración</title>
    <style>
        * {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }
        
        body {
            margin: 0;
            background-image: url('Imagenes/bg.jpg');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
        }
        
        header {
            overflow: hidden;
            padding: 20px;
            background-color: #f9f9f9;
        }
        
        .container-center {
            max-width: auto;
            margin: 0 auto;
        }
        
        .button {
            padding: 10px 20px;
            background-color: #000000;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            transition: background-color 0.3s ease;
        }

        .button:hover {
            background-color: #0057b37c;
            color: white;
        }

        header h1 {
            float: left;
            font-size: 28px;
            margin-top: 4px;
        }
        
        nav {
            float: right;
        }
        
        nav a, nav button {
            margin: 0 15px;
            text-decoration: none;
            color: black;
            display: inline-block;
        }
        
        nav button {
            background-color: #f9f9f9;
            color: black;
            border: 1px solid black;
            padding: 10px 15px;
            border-radius: 5px;
        }
        
        nav button+button {
            display: none;
        }
        
        nav button:hover {
            background-color: black;
            color: white;
            cursor: pointer;
        }

        .logo img {
            width: 8vw;
            height: auto;
        }
        
        .card-container {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            grid-template-rows: repeat(3, 1fr);
            gap: 20px;
            padding: 20px;
        }
        
        .card {
            background-color: white;
            border-radius: 5px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        #card7 {
            grid-column: 1/2;
            grid-row: 1/20;
            background-color: #fafafa;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
            padding-top: 10px;
        }

        #card8 {
            grid-column: 2/3;
            grid-row: 1/20;
            background-color: rgb(0, 0, 0);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
            padding-top: 10px;
        } 

        .card7, .card8 {
            width: 40vw;
            height: auto;
            display: block;
        }

        .image-button7, .image-button8 {
            width: 100%;
            display: flex;
            justify-content: center;
            background: transparent;
            border: none;
            overflow: hidden;
            margin: 0;
            padding: 0;
            cursor: pointer;
        }

        .image-text7 {
            font-size: 18px;
            margin-top: 10px;
            font-family: 'Arial', sans-serif;
            color: #000;
            font-weight: bold;
            width: 100%;
            order: 1;
            margin-top: 10px;
        }

        .image-text8 {
            font-size: 18px;
            margin-top: 10px;
            font-family: 'Arial', sans-serif;
            color: #fff;
            font-weight: bold;
            width: 100%;
            order: 1;
            margin-top: 10px;
        }

        .card-link {
            text-decoration: none;
            width: 100%;
        }
        
        footer {
            background-color: rgba(0, 0, 0, 0.700);
            color: white;
            padding: 50px 0;
            overflow: hidden;
        }
        
        footer .container-center div {
            float: left;
            width: 20%;
            text-align: center;
        }
        
        footer h2, footer h3 {
            font-size: 28px;
            margin: 30px 0;
        }
        
        footer p, footer a {
            margin: 7px 0;
            display: block;
            color: white;
            text-decoration: none;
        }
        
        footer a:hover {
            color: gray;
        }
        
        @media screen and (max-width: 768px) {
            .logo img {
                width: 80px;
            }

            nav a {
                display: none;
            }
        
            nav button:nth-child(5) {
                display: none;
            }
        
            nav button+button {
                display: block;
            }

            .button {
                padding: 10px 20px;
                font-size: 14px;
            }

            .card-container {
                display: flex;
                flex-wrap: wrap;
                justify-content: space-between;
            }

            .card {
                width: 100%;
                margin-bottom: 20px;
            }

            #card7, #card8 {
                grid-column: auto;
                grid-row: auto;
            }

            .card7, .card8 {
                width: 90vw;
            }
        
            footer .container-center {
                width: 100%;
            }
        
            footer .container-center div {
                width: 100%;
                float: none;
                display: block;
                clear: both;
                text-align: center;
            }
        }
    </style>
</head>
<body>

<header>
    <a class="logo" href="#"><img src="Imagenes/Tec.png" alt="Logo"></a>
    <nav>
        <script>
            function confirmLogout() {
                var response = confirm("¿Estás seguro de que deseas cerrar sesión?");
                if (response) {
                    window.location.href = '../controllers/AuthController.php?action=logout';
                }
            }
        </script>
        <a href="javascript:void(0);" onclick="confirmLogout()" class="button">Cerrar Sesión</a>
    </nav>
</header>

<div class="card-container">
    <div class="card" id="card7">
        <a href="../controllers/CarreraController.php?action=index" class="card-link">
            <button class="image-button7">
                <img src="Imagenes/carreras.jpg" alt="Carreras" class="card7">
            </button>
        </a>
        <span class="image-text7">Carreras</span>
    </div>

    <div class="card" id="card8">
        <a href="../controllers/MateriaController.php?action=index" class="card-link">
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