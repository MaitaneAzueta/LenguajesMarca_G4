<?php
session_start();
$servername = "localhost:3359";
$username = "root";
$password = "";
$dbname = "reto2_g4";

$conexion = new mysqli($servername, $username, $password, $dbname);

if (isset($_GET['id']) && $_GET['id'] != '0') {
    $idSesion = (int)$_GET['id'];
    $_SESSION['ultima_sesion_comprada'] = $idSesion;

    // Intentamos insertar, pero si falla por IDCompra, igual guardamos la sesión
    $sql = "INSERT INTO entrada (NumPers, IDSesion, IDCompra) VALUES (1, $idSesion, 1)";
    $conexion->query($sql);
    
    header("Location: carrito.php");
    exit();
} else {
    header("Location: index.php?error=no_id");
    exit();
}
?>
