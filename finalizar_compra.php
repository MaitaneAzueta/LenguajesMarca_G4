<?php
session_start();

$servername = "localhost:3359";
$username = "root";
$password = "";
$dbname = "reto2_g4";

$conexion = new mysqli($servername, $username, $password, $dbname);

if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

$accion = isset($_POST['accion']) ? $_POST['accion'] : "";

if ($accion === "confirmar") {
    if (isset($_SESSION['carrito_sesion'])) {
        $idSesion = $_SESSION['carrito_sesion'];
        $dniCliente = "67856221B";
        $sqlCompra = "INSERT INTO compra (DNICliente, descuento, Canal, Importe) 
        VALUES ('$dniCliente', 0.0, 1, 15.0)";
        
        if ($conexion->query($sqlCompra)) {
            $idCompraGenerado = $conexion->insert_id;

            $sqlEntrada = "INSERT INTO entrada (NumPers, IDSesion, IDCompra) 
                           VALUES (1, $idSesion, $idCompraGenerado)";
            
            if ($conexion->query($sqlEntrada)) {
                unset($_SESSION['carrito_sesion']);
                header("Location: index.php?status=success");
                exit();
            } else {
                echo "Error al crear entrada: " . $conexion->error;
            }
        } else {
            echo "Error al crear compra: " . $conexion->error;
        }
    } else {
        echo "No hay ninguna sesión en el carrito.";
    }
} else {
    if (isset($_SESSION['carrito_sesion'])) {
        unset($_SESSION['carrito_sesion']);
    }
    header("Location: index.php");
    exit();
}

$conexion->close();
?>