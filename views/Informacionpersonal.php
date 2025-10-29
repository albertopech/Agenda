<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Información Personal - Mi Agenda</title>
    
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

        .container {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 100%;
        }

        form {
            display: flex;
            flex-direction: column;
            width: 100%;
            max-width: 450px;
            box-shadow: 0 0 30px rgba(0, 0, 0, 0.3);
            border-radius: 15px;
            background-color: #EEEEEE;
            padding: 35px;
            animation: fadeIn 0.5s ease-in;
            max-height: 90vh;
            overflow-y: auto;
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
            font-size: 28px;
            text-transform: uppercase;
            color: #2E2E2E;
            margin-bottom: 25px;
        }

        label p {
            font-size: 16px;
            color: #575757;
            margin-bottom: 8px;
            margin-top: 5px;
        }

        input[type="text"],
        input[type="email"],
        input[type="date"],
        input[type="tel"] {
            padding: 15px 11px;
            border: 1px solid #00ADEF;
            border-radius: 25px;
            margin-bottom: 20px;
            background-color: #FAFAFC;
            outline: none;
            color: #00ADEF;
            font-size: 16px;
            width: 100%;
            transition: all 0.3s ease;
        }

        input[type="text"]:focus,
        input[type="email"]:focus,
        input[type="date"]:focus,
        input[type="tel"]:focus {
            border-color: #04b8ff;
            box-shadow: 0 0 8px rgba(0, 173, 239, 0.3);
            transform: scale(1.01);
        }

        input[type="date"] {
            color: #575757;
        }

        input[type="date"]::-webkit-calendar-picker-indicator {
            cursor: pointer;
            filter: invert(0.5) sepia(1) saturate(5) hue-rotate(175deg);
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
            margin-top: 10px;
        }

        .btn-1:hover {
            background-color: #04b8ff;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 173, 239, 0.4);
        }

        .btn-1:active {
            transform: translateY(0);
        }

        .alert {
            padding: 12px 15px;
            border-radius: 15px;
            margin-bottom: 20px;
            font-size: 15px;
            text-align: center;
            animation: slideIn 0.3s ease-out;
            background-color: #f8d7da;
            border: 1px solid #f5c2c7;
            color: #842029;
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

        /* Scroll personalizado para el formulario */
        form::-webkit-scrollbar {
            width: 8px;
        }

        form::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }

        form::-webkit-scrollbar-thumb {
            background: #00ADEF;
            border-radius: 10px;
        }

        form::-webkit-scrollbar-thumb:hover {
            background: #04b8ff;
        }

        /* Media query para teléfonos */
        @media only screen and (max-width: 600px) {
            form {
                width: 95%;
                padding: 25px;
                max-height: 85vh;
            }
            
            h2 {
                font-size: 22px;
            }

            input[type="text"],
            input[type="email"],
            input[type="date"],
            input[type="tel"] {
                font-size: 14px;
                padding: 13px 10px;
                margin-bottom: 15px;
            }

            label p {
                font-size: 14px;
            }
        }
    </style>
    
    <script>
        window.addEventListener('popstate', function (event) {
            if (event.state && event.state.isBackNavigation) {
                if (confirm("Si regresa, se perderán todos los cambios.")) {
                    history.back();
                } else {
                    history.pushState({isBackNavigation: true}, "");
                }
            }
        });
        history.pushState({isBackNavigation: true}, "");
    </script>
</head>
<body>
    <div class="container">
        <form action="../controllers/UsuarioController.php?action=informacion_personal" method="post">
            <h2>Información Personal</h2>
            
            <?php if (isset($mensajeError) && $mensajeError != ""): ?>
                <div class='alert'><?= htmlspecialchars($mensajeError) ?></div>
            <?php endif; ?>

            <label for="nombres"><p>Nombres:</p></label>
            <input type="text" name="nombres" id="nombres" required placeholder="Ingresa tus nombres">

            <label for="primerapellido"><p>Primer Apellido:</p></label>
            <input type="text" name="primerapellido" id="primerapellido" required placeholder="Ingresa tu primer apellido">

            <label for="segundoapellido"><p>Segundo Apellido:</p></label>
            <input type="text" name="segundoapellido" id="segundoapellido" placeholder="Ingresa tu segundo apellido (opcional)">

            <label for="fecha_nacimiento"><p>Fecha de Nacimiento:</p></label>
            <input type="date" name="fecha_nacimiento" id="fecha_nacimiento" required>

            <label for="telefono"><p>Teléfono:</p></label>
            <input type="tel" id="telefono" name="telefono" required title="El número debe ser de 10 dígitos" maxlength="10" minlength="10" placeholder="10 dígitos">

            <label for="email"><p>Email:</p></label>
            <input type="email" name="email" id="email" required placeholder="correo@ejemplo.com">

            <?php if (isset($_SESSION['registro_usuario']) && $_SESSION['registro_usuario']['tiposusuarioid'] != 1): ?>
                <label for="RFC"><p>RFC:</p></label>
                <input type="text" name="RFC" id="RFC" placeholder="Ingresa tu RFC">
            <?php endif; ?>

            <input type="submit" value="Guardar" class="btn-1">
        </form>
    </div>

    <script>
        // Validación para que solo acepte números en el campo teléfono
        document.getElementById('telefono').addEventListener('input', function (e) {
            var x = e.target.value.replace(/\D/g, '');
            e.target.value = x;
        });

        // Limitar la fecha de nacimiento (mayor de 5 años)
        var today = new Date();
        var maxDate = new Date(today.getFullYear() - 5, today.getMonth(), today.getDate());
        var minDate = new Date(today.getFullYear() - 100, today.getMonth(), today.getDate());
        
        document.getElementById('fecha_nacimiento').max = maxDate.toISOString().split('T')[0];
        document.getElementById('fecha_nacimiento').min = minDate.toISOString().split('T')[0];
    </script>
</body>
</html>