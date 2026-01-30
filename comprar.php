<?php
session_start();

if (isset($_GET['id'])) {
    $idSesion = (int)$_GET['id'];

    if ($idSesion !== 0) {
        $_SESSION['carrito_sesion'] = $idSesion;
        header("Location: carrito.php");
        exit();
    } else {
        header("Location: index.php?error=id_invalido");
        exit();
    }
} else {
    header("Location: index.php?error=sin_id");
    exit();
}
?>
