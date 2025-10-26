<?php
session_start();  // Inicia la sesión

include 'Conexion.php';

$mensajeError = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recogiendo los datos POST
    $usuariosid = isset($_POST["usuariosid"]) ? $_POST["usuariosid"] : '';
    $codigo_postal = isset($_POST["codigo_postal"]) ? $_POST["codigo_postal"] : '';
    $municipio = isset($_POST["municipio"]) ? htmlspecialchars($_POST["municipio"]) : '';
    $estado = isset($_POST["estado"]) ? htmlspecialchars($_POST["estado"]) : '';
    $ciudad = isset($_POST["ciudad"]) ? htmlspecialchars($_POST["ciudad"]) : '';
    $colonia = isset($_POST["colonia"]) ? htmlspecialchars($_POST["colonia"]) : '';
    $calle_principal = isset($_POST["calle_principal"]) ? htmlspecialchars($_POST["calle_principal"]) : '';
    $primer_cruzamiento = isset($_POST["primer_cruzamiento"]) ? htmlspecialchars($_POST["primer_cruzamiento"]) : '';
    $segundo_cruzamiento = isset($_POST["segundo_cruzamiento"]) ? htmlspecialchars($_POST["segundo_cruzamiento"]) : '';
    $referencias = isset($_POST["referencias"]) ? htmlspecialchars($_POST["referencias"]) : '';
    $numero_exterior = isset($_POST["numero_exterior"]) ? $_POST["numero_exterior"] : '';
    $numero_interior = isset($_POST["numero_interior"]) ? $_POST["numero_interior"] : '';

    // Realiza las validaciones necesarias aquí antes de realizar la inserción en la base de datos
    if (empty($usuariosid) || empty($municipio) || empty($estado) || empty($ciudad) || empty($colonia) || empty($calle_principal)) {
        $mensajeError = "Por favor, complete todos los campos obligatorios.";
    } elseif (!is_numeric($codigo_postal) || strlen($codigo_postal) !== 5) {
        $mensajeError = "Ingrese un código postal válido de 5 dígitos.";
    } elseif (!is_numeric($numero_exterior) && !empty($numero_exterior)) {
        $mensajeError = "El número exterior debe ser un valor numérico.";
    } elseif (!is_numeric($numero_interior) && !empty($numero_interior)) {
        $mensajeError = "El número interior debe ser un valor numérico.";
    } else {
        // Almacenar datos en la sesión si no hay errores
        $_SESSION['informacion_contacto'] = [
            'codigo_postal' => $codigo_postal,
            'municipio' => $municipio,
            'estado' => $estado,
            'ciudad' => $ciudad,
            'colonia' => $colonia,
            'calle_principal' => $calle_principal,
            'primer_cruzamiento' => $primer_cruzamiento,
            'segundo_cruzamiento' => $segundo_cruzamiento,
            'referencias' => $referencias,
            'numero_exterior' => $numero_exterior,
            'numero_interior' => $numero_interior
        ];

        header("Location: InformacionAcademica_estudiante.php");
        exit();
    }
}

// Obtener el ID del último usuario registrado
$lastUserIdQuery = "SELECT ID_usuarios FROM Usuarios ORDER BY ID_usuarios DESC LIMIT 1";
$lastUserIdResult = mysqli_query($conn, $lastUserIdQuery);
$lastUser = mysqli_fetch_assoc($lastUserIdResult);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Información de Contacto</title>
    <link href="Estilo2.css" rel="stylesheet"/>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        .section { display: none; }
        .section.active { display: block; }
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

    <form action="Informacioncontacto.php" method="post">

        <h2>Información de Contacto</h2>

        <?php
        if (!empty($mensajeError)) {
            echo "<div class='alert'>" . htmlspecialchars($mensajeError) . "</div>";
        }
        ?>

        <input type="hidden" name="usuariosid" value="<?= htmlspecialchars($lastUser['ID_usuarios']); ?>">

        <!-- Sección 1 -->
        <div class="section active" id="section1">
            <div>
                <label for="codigo_postal"><p>Código Postal:</p></label>
                <input type="text" id="codigo_postal" name="codigo_postal" required pattern="\d{5}" title="Solo se aceptan 5 dígitos" maxlength="5" minlength="5">
            </div>
            <div>
                <label for="municipio"><p>Municipio:</p></label>
                <input type="text" id="municipio" name="municipio" required>
            </div>
            <div>
                <label for="estado"><p>Estado:</p></label>
                <input type="text" id="estado" name="estado" required>
            </div>
            <div>
                <label for="ciudad"><p>Ciudad:</p></label>
                <input type="text" id="ciudad" name="ciudad" required>
            </div>
            <div>
                <label for="colonia">Colonia:</label>
                <select name="colonia" id="colonia" required></select>
            </div>
            <button type="button" id="next1" class="button-custom">Siguiente</button>
        </div>

        <!-- Sección 2 -->
        <div class="section" id="section2">
            <div>
                <label for="calle_principal"><p>Calle Principal:</p></label>
                <input type="text" id="calle_principal" name="calle_principal" required>
            </div>
            <div>
                <label for="primer_cruzamiento"><p>Primer Cruzamiento:</p></label>
                <input type="text" id="primer_cruzamiento" name="primer_cruzamiento">
            </div>
            <div>
                <label for="segundo_cruzamiento"><p>Segundo Cruzamiento:</p></label>
                <input type="text" id="segundo_cruzamiento" name="segundo_cruzamiento">
            </div>
            <div>
                <label for="referencias"><p>Referencias:</p></label>
                <input type="text" id="referencias" name="referencias">
            </div>
            <div>
                <label for="numero_exterior"><p>Número Exterior:</p></label>
                <input type="text" id="numero_exterior" name="numero_exterior" required>
            </div>
            <div>
                <label for="numero_interior"><p>Número Interior:</p></label>
                <input type="text" id="numero_interior" name="numero_interior">
            </div>
            <button type="button" id="prev1" class="button-custom">Anterior</button>
            <button type="submit" class="button-custom">Guardar</button>
        </div>

    </form>

    <script>
        $(document).ready(function() {
            $('#next1').click(function() {
                $('#section1').removeClass('active');
                $('#section2').addClass('active');
            });

            $('#prev1').click(function() {
                $('#section2').removeClass('active');
                $('#section1').addClass('active');
            });

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

                                    // Llenar el dropdown de colonia
                                    let coloniaDropdown = $('#colonia');
                                    coloniaDropdown.empty();
                                    data.postalcodes.forEach(function(place) {
                                        coloniaDropdown.append($('<option>', {
                                            value: place.placeName,
                                            text: place.placeName
                                        }));
                                    });
                                }
                            },
                            error: function() {
                                console.log('Error al obtener información del código postal.');
                            }
                        });
                    }
                }, 600);
            });
        });
    </script>

</body>
</html>