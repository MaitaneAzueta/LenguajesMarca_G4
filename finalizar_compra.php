<?php
// Iniciar la sesión para acceder al carrito del usuario
session_start();

// Establecer conexión con la base de datos MySQL (host:puerto, usuario, contraseña, nombre_bd)
$servername = "localhost:3359";
$username = "root";
$password = "";
$dbname = "reto2_g4";

// Crear la conexión con MySQL
$conexion = new mysqli($servername, $username, $password, $dbname);

// Comprobar si la conexión falló y detener la ejecución en caso de error
if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

// Comprobar qué boton se pulso en el formulario (confirmar o cancelar)
$accion = isset($_POST['accion']) ? $_POST['accion'] : "";

// Si el usuario confirma la compra:
if ($accion === "confirmar") {
// Verificar que realmente hay algo seleccionado para comprar
    if (isset($_SESSION['carrito_sesion'])) {
        $idSesion = $_SESSION['carrito_sesion'];
        //$dniCliente = "67856221B"; // DNI de ejemplo, al hacer la compra se almacenara el del usuario
        $dniCliente = $_SESSION['dni']; // Recuperar el DNI del cliente desde la sesión

// Insertar el registro principal en la tabla 'compra'
// Se definen valores por defecto: descuento 0, canal 1 e importe 15.0
        $sqlCompra = "INSERT INTO compra (DNICliente, descuento, Canal, Importe) 
                      VALUES ('$dniCliente', 0.0, 1, 15.0)";
        
        if ($conexion->query($sqlCompra)) {
// Obtener el ID autogenerado de la compra que acabamos de crear
            $idCompraGenerado = $conexion->insert_id;

// Insertar el detalle en la tabla 'entrada' vinculándola con la compra anterior
            $sqlEntrada = "INSERT INTO entrada (NumPers, IDSesion, IDCompra) 
                           VALUES (1, $idSesion, $idCompraGenerado)";
            
            if ($conexion->query($sqlEntrada)) {
// Si todo sale bien, vaciamos el carrito de la sesión
                unset($_SESSION['carrito_sesion']);
// Redirigimos al inicio con un mensaje de éxito
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

// Se cancela la compra o no termina de confirmarse
} else {
// Si decide cancelar, se limpia el carrito de la sesión
    if (isset($_SESSION['carrito_sesion'])) {
        unset($_SESSION['carrito_sesion']);
    }
// Se devuelve al usuario a la página principal
    header("Location: index.php");
    exit();
}

//Cierre de la conexion php
$conexion->close();
?>