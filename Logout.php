<?php
// Iniciar la sesión
session_start();

// Destruir todas las variables de sesión
$_SESSION = array();

// Finalmente, destruye la sesión
session_destroy();

// Redireccionar al usuario a index.php
header("Location: index.php");
exit();
?>