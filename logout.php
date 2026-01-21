<?php
// Inicia un php/sesion
session_start();
// Elimina los datos de la sesión
session_destroy();
// Redirige a index.html
header("Location: index.html");
exit();
?>