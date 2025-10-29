<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Información Académica - Mi Agenda</title>
    
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

        .form-group {
            margin-bottom: 20px;
        }

        label p {
            font-size: 16px;
            color: #575757;
            margin-bottom: 8px;
            margin-top: 0;
        }

        input[type="text"],
        select {
            padding: 15px 11px;
            border: 1px solid #00ADEF;
            border-radius: 25px;
            background-color: #FAFAFC;
            outline: none;
            color: #00ADEF;
            font-size: 16px;
            width: 100%;
            transition: all 0.3s ease;
        }

        input[type="text"]:focus,
        select:focus {
            border-color: #04b8ff;
            box-shadow: 0 0 8px rgba(0, 173, 239, 0.3);
            transform: scale(1.01);
        }

        select {
            cursor: pointer;
            appearance: none;
            background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%2300ADEF' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3e%3cpolyline points='6 9 12 15 18 9'%3e%3c/polyline%3e%3c/svg%3e");
            background-repeat: no-repeat;
            background-position: right 15px center;
            background-size: 20px;
            padding-right: 40px;
        }

        small.form-text p {
            font-size: 13px;
            color: #888;
            margin-top: 5px;
            font-style: italic;
        }

        .button-custom {
            background-color: #00ADEF;
            font-size: 15px;
            color: #ffffff;
            text-transform: uppercase;
            cursor: pointer;
            padding: 17px 11px;
            border: none;
            border-radius: 25px;
            margin-top: 10px;
            transition: all 0.3s ease;
            width: 100%;
        }

        .button-custom:hover {
            background-color: #04b8ff;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 173, 239, 0.4);
        }

        .button-custom:active {
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

        /* Media query para teléfonos */
        @media only screen and (max-width: 600px) {
            form {
                width: 95%;
                padding: 25px;
            }
            
            h2 {
                font-size: 22px;
            }

            input[type="text"],
            select {
                font-size: 14px;
                padding: 13px 10px;
            }

            label p {
                font-size: 14px;
            }

            .button-custom {
                padding: 15px 10px;
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

        document.addEventListener('DOMContentLoaded', function() {
            // Validación del número de control (solo 8 dígitos)
            var numcontrolInput = document.getElementById('numcontrol');
            if (numcontrolInput) {
                numcontrolInput.addEventListener('input', function() {
                    var value = this.value.replace(/\D/g, '');
                    this.value = value.substring(0, 8);
                });
            }

            // Validación del promedio (formato XX.X)
            var promedioInput = document.getElementById('promedio');
            if (promedioInput) {
                promedioInput.addEventListener('input', function() {
                    var value = this.value;
                    // Permitir solo números y un punto decimal
                    value = value.replace(/[^\d.]/g, '');
                    
                    // Limitar a formato XX.X
                    var parts = value.split('.');
                    if (parts.length > 2) {
                        value = parts[0] + '.' + parts.slice(1).join('');
                    }
                    if (parts[0].length > 2) {
                        parts[0] = parts[0].substring(0, 2);
                    }
                    if (parts[1] && parts[1].length > 1) {
                        parts[1] = parts[1].substring(0, 1);
                    }
                    
                    this.value = parts.join('.');
                });
            }
        });
    </script>
</head>
<body>
    <div class="container">
        <form action="../controllers/UsuarioController.php?action=completar_registro" method="post">
            <h2>Información Académica</h2>
            
            <?php if (isset($mensaje) && !empty($mensaje)): ?>
                <div class='alert'><?= htmlspecialchars($mensaje) ?></div>
            <?php endif; ?>

            <div class="form-group">
                <label for="periodoId"><p>Periodo:</p></label>
                <select name="periodoId" id="periodoId" required>
                    <option value="">Selecciona un periodo</option>
                    <?php if (isset($periodos)): ?>
                        <?php foreach ($periodos as $periodo): ?>
                            <option value="<?= htmlspecialchars($periodo['ID_periodo']) ?>">
                                <?= htmlspecialchars($periodo['nombre']) ?>
                            </option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>
            </div>

            <div class="form-group">
                <label for="carreraId"><p>Carrera:</p></label>
                <select name="carreraId" id="carreraId" required>
                    <option value="">Selecciona una carrera</option>
                    <?php if (isset($carreras)): ?>
                        <?php foreach ($carreras as $carrera): ?>
                            <option value="<?= htmlspecialchars($carrera['ID_carrera']) ?>">
                                <?= htmlspecialchars($carrera['nombre']) ?>
                            </option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>
            </div>

            <div class="form-group">
                <label for="numcontrol"><p>Número de Control:</p></label>
                <input type="text" name="numcontrol" id="numcontrol" pattern="\d{8}" title="Debe tener exactamente 8 dígitos" placeholder="8 dígitos" required>
            </div>

            <div class="form-group">
                <label for="semestre"><p>Semestre:</p></label>
                <select name="semestre" id="semestre" required>
                    <option value="">Selecciona un semestre</option>
                    <?php for ($i = 1; $i <= 12; $i++): ?>
                        <option value="<?= $i ?>"><?= $i ?>° Semestre</option>
                    <?php endfor; ?>
                </select>
            </div>

            <div class="form-group">
                <label for="promedio"><p>Promedio:</p></label>
                <input type="text" name="promedio" id="promedio" pattern="^\d{2}\.\d$" title="2 dígitos y 1 decimal, por ejemplo, 90.0" placeholder="Ej: 90.0" required>
                <small class="form-text"><p>Ejemplo válido: 90.0 (dos dígitos, punto, un decimal)</p></small>
            </div>

            <button type="submit" class="button-custom">Completar Registro</button>
        </form>
    </div>
</body>
</html>