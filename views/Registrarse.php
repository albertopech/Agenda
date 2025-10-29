<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrarse - Mi Agenda</title>
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Helvetica Neue', sans-serif;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            background-image: url('../Imagenes/bg.jpg');
            background-position: center;
            background-repeat: no-repeat;
            background-size: cover;
            padding: 20px;
        }

        form {
            display: flex;
            flex-direction: column;
            width: 100%;
            max-width: 400px;
            box-shadow: 0 0 30px rgba(0, 0, 0, 0.3);
            border-radius: 15px;
            background-color: #EEEEEE;
            padding: 35px;
            animation: fadeIn 0.5s ease-in;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        h2 {
            text-align: center;
            font-size: 30px;
            text-transform: uppercase;
            color: #2E2E2E;
            margin-bottom: 25px;
        }

        label p {
            font-size: 17px;
            color: #575757;
            margin-bottom: 10px;
        }

        input[type="text"],
        input[type="password"] {
            padding: 17px 11px;
            border: 1px solid #00ADEF;
            border-radius: 25px;
            margin-bottom: 25px;
            background-color: #FAFAFC;
            outline: none;
            color: #00ADEF;
            font-size: 16px;
            width: 100%;
            transition: all 0.3s ease;
        }

        input[type="text"]:focus,
        input[type="password"]:focus {
            border-color: #04b8ff;
            box-shadow: 0 0 8px rgba(0, 173, 239, 0.3);
            transform: scale(1.01);
        }

        .btn-1 {
            background-color: #00ADEF;
            font-size: 15px;
            color: #ffffff;
            text-transform: uppercase;
            cursor: pointer;
            border: none;
            padding: 17px 11px;
            border-radius: 25px;
            transition: all 0.3s ease;
            width: 100%;
        }

        .btn-1:hover {
            background-color: #04b8ff;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 173, 239, 0.4);
        }

        .btn-1:active {
            transform: translateY(0);
        }

        .text-center {
            text-align: center;
            margin-top: 20px;
        }

        .text-center p {
            font-size: 15px;
            color: #575757;
            margin-bottom: 10px;
        }

        .text-center a {
            color: #00ADEF;
            text-decoration: none;
            font-weight: 600;
            font-size: 16px;
            transition: all 0.3s ease;
            display: inline-block;
        }

        .text-center a:hover {
            color: #04b8ff;
            text-decoration: underline;
            transform: scale(1.05);
        }

        .alert {
            padding: 12px 15px;
            border-radius: 15px;
            margin-bottom: 20px;
            font-size: 15px;
            text-align: center;
            animation: slideIn 0.3s ease-out;
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .alert-danger {
            background-color: #f8d7da;
            border: 1px solid #f5c2c7;
            color: #842029;
        }

        /* Media query para teléfonos */
        @media only screen and (max-width: 600px) {
            form {
                width: 95%;
                padding: 25px;
            }
            
            h2 {
                font-size: 24px;
            }

            input[type="text"],
            input[type="password"] {
                font-size: 14px;
            }
        }
    </style>
    
</head>
<body>
    
    <form action="../controllers/AuthController.php?action=registro" method="post">
        <h2>Registrarse</h2>

        <?php if (isset($mensajeError) && $mensajeError != ""): ?>
            <div class='alert alert-danger'><?= htmlspecialchars($mensajeError) ?></div>
        <?php endif; ?>

        <label for="nombre"><p>Nombre Usuario:</p></label>
        <input type="text" name="nombre" id="nombre" required placeholder="Ingresa tu nombre de usuario">

        <label for="contrasena"><p>Contraseña:</p></label>
        <input type="password" name="contrasena" id="contrasena" required placeholder="Ingresa tu contraseña">

        <input type="submit" value="Registrarse" class="btn-1">
        
        <div class="text-center">
            <p>¿Ya tienes cuenta? <a href="../controllers/AuthController.php?action=login">Inicia sesión</a></p>
        </div>
    </form>
    
</body>
</html>