<?php
// %_SESSION para trabajar
session_start();

// Parametros para la conexion a la base de datos
$servername = "localhost:3359";
$username = "root";
$password = "";
$dbname = "reto2_g4";

// Se "hace" la conexion
$conn = new mysqli($servername, $username, $password, $dbname, 3359);

// Se verifica la conexion
if ($conn->connect_error) {
    die("Fallo en la conexión: " . $conn->connect_error);
}

// Se recuperan los datos introducidos en el formulario de login.html
$usuario = $_POST['usuario']; 
$contraseña = $_POST['clave'];

// La consulta SQL
$sql = "SELECT * FROM cliente WHERE Nombre = '$usuario' AND Contraseña = '$contraseña'";
// Ejecutar la consulta
$result = $conn->query($sql);

// Comprobar que existe el usuario en la BBDD
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $_SESSION['cliente'] = $row['Nombre']; // Guardaremos el nombre del usuario para enseñarlo despues
        $_SESSION['dni'] = $row['DNICliente']; // Guardaremos el DNI del usuario para futuras consultas
    }
    // Si existe en la BBDD iniciara sesion, entrara a menu.php y mostrara el mensaje de arriba
    header("Location: index.php");
    exit();
} else {
    // Manda un error via url
    header ("Location: ../index.html?errorea=1");
    exit();
}

// Cerrar la conexion
$conn->close();
?>
