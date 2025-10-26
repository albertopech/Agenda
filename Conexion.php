<?php
$servidor = "localhost";
$usuario = "root";
$password = "";
$base_datos = "BD_Agenda";

// Crear conexión
$conn = mysqli_connect($servidor, $usuario, $password, $base_datos);

// Verificar conexión
if (!$conn) {
    die("Error de conexión: " . mysqli_connect_error());
}

// Configurar charset UTF-8
mysqli_set_charset($conn, "utf8");
?>