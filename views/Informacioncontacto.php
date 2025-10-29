<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Información de Contacto - Mi Agenda</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
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
        select {
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

        .button-custom {
            background-color: #00ADEF;
            font-size: 15px;
            color: #ffffff;
            text-transform: uppercase;
            cursor: pointer;
            padding: 15px 20px;
            border: none;
            border-radius: 25px;
            margin: 10px 0;
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

        /* Secciones */
        .section {
            display: none;
            animation: fadeInSection 0.3s ease-in;
        }

        .section.active {
            display: block;
        }

        @keyframes fadeInSection {
            from {
                opacity: 0;
                transform: translateX(20px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        /* Scroll personalizado */
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
            select {
                font-size: 14px;
                padding: 13px 10px;
                margin-bottom: 15px;
            }

            label p {
                font-size: 14px;
            }

            .button-custom {
                padding: 13px 18px;
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
    <form action="../controllers/UsuarioController.php?action=informacion_contacto" method="post">
        <h2>Información de Contacto</h2>

        <?php if (isset($mensajeError) && !empty($mensajeError)): ?>
            <div class='alert'><?= htmlspecialchars($mensajeError) ?></div>
        <?php endif; ?>

        <?php if (isset($lastUser)): ?>
            <input type="hidden" name="usuariosid" value="<?= htmlspecialchars($lastUser['ID_usuarios']) ?>">
        <?php endif; ?>

        <!-- Sección 1: Dirección General -->
        <div class="section active" id="section1">
            <div>
                <label for="codigo_postal"><p>Código Postal:</p></label>
                <input type="text" id="codigo_postal" name="codigo_postal" required pattern="\d{5}" title="Solo se aceptan 5 dígitos" maxlength="5" minlength="5" placeholder="5 dígitos">
            </div>
            <div>
                <label for="municipio"><p>Municipio:</p></label>
                <input type="text" id="municipio" name="municipio" required placeholder="Se llenará automáticamente">
            </div>
            <div>
                <label for="estado"><p>Estado:</p></label>
                <input type="text" id="estado" name="estado" required placeholder="Se llenará automáticamente">
            </div>
            <div>
                <label for="ciudad"><p>Ciudad:</p></label>
                <input type="text" id="ciudad" name="ciudad" required placeholder="Se llenará automáticamente">
            </div>
            <div>
                <label for="colonia"><p>Colonia:</p></label>
                <select name="colonia" id="colonia" required>
                    <option value="">Selecciona una colonia</option>
                </select>
            </div>
            <button type="button" id="next1" class="button-custom">Siguiente</button>
        </div>

        <!-- Sección 2: Detalles de Dirección -->
        <div class="section" id="section2">
            <div>
                <label for="calle_principal"><p>Calle Principal:</p></label>
                <input type="text" id="calle_principal" name="calle_principal" required placeholder="Nombre de la calle">
            </div>
            <div>
                <label for="primer_cruzamiento"><p>Primer Cruzamiento:</p></label>
                <input type="text" id="primer_cruzamiento" name="primer_cruzamiento" placeholder="Entre calle (opcional)">
            </div>
            <div>
                <label for="segundo_cruzamiento"><p>Segundo Cruzamiento:</p></label>
                <input type="text" id="segundo_cruzamiento" name="segundo_cruzamiento" placeholder="Y calle (opcional)">
            </div>
            <div>
                <label for="referencias"><p>Referencias:</p></label>
                <input type="text" id="referencias" name="referencias" placeholder="Puntos de referencia (opcional)">
            </div>
            <div>
                <label for="numero_exterior"><p>Número Exterior:</p></label>
                <input type="text" id="numero_exterior" name="numero_exterior" required placeholder="Ej: 123">
            </div>
            <div>
                <label for="numero_interior"><p>Número Interior:</p></label>
                <input type="text" id="numero_interior" name="numero_interior" placeholder="Ej: Depto 4 (opcional)">
            </div>
            <button type="button" id="prev1" class="button-custom">Anterior</button>
            <button type="submit" class="button-custom">Guardar</button>
        </div>
    </form>

    <script>
        $(document).ready(function() {
            // Navegación entre secciones
            $('#next1').click(function() {
                // Validar campos de la sección 1 antes de avanzar
                let valid = true;
                $('#section1 input[required], #section1 select[required]').each(function() {
                    if (!this.checkValidity()) {
                        valid = false;
                        $(this).focus();
                        return false;
                    }
                });

                if (valid) {
                    $('#section1').removeClass('active');
                    $('#section2').addClass('active');
                }
            });

            $('#prev1').click(function() {
                $('#section2').removeClass('active');
                $('#section1').addClass('active');
            });

            // Validación de código postal (solo números)
            $('#codigo_postal').on('input', function() {
                this.value = this.value.replace(/\D/g, '');
            });

            // Búsqueda automática de dirección por código postal
            let timer;
            $('#codigo_postal').on('keyup', function() {
                clearTimeout(timer);
                timer = setTimeout(function() {
                    let codigoPostal = $('#codigo_postal').val();
                    if (codigoPostal.length === 5) {
                        $.ajax({
                            url: `https://secure.geonames.org/postalCodeLookupJSON?postalcode=${codigoPostal}&country=MX&username=valisama`,
                            method: 'GET',
                            success: function(data) {
                                if (data && data.postalcodes.length > 0) {
                                    let place = data.postalcodes[0];
                                    $('#municipio').val(place.adminName2 || '');
                                    $('#estado').val(place.adminName1 || '');
                                    $('#ciudad').val(place.adminName3 || '');

                                    // Llenar el dropdown de colonias
                                    let coloniaDropdown = $('#colonia');
                                    coloniaDropdown.empty();
                                    coloniaDropdown.append($('<option>', {
                                        value: '',
                                        text: 'Selecciona una colonia'
                                    }));
                                    data.postalcodes.forEach(function(place) {
                                        coloniaDropdown.append($('<option>', {
                                            value: place.placeName,
                                            text: place.placeName
                                        }));
                                    });
                                } else {
                                    alert('Código postal no encontrado. Por favor verifica e intenta de nuevo.');
                                }
                            },
                            error: function() {
                                console.log('Error al obtener información del código postal.');
                                alert('Error al buscar el código postal. Por favor intenta más tarde.');
                            }
                        });
                    }
                }, 600);
            });
        });
    </script>
</body>
</html>