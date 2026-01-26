<!--En la informacion de compra debera aparecer numero de entradas, 
sala, pelicula, fecha y hora-->

<!--Aparte debera de tener un apartado de confirmacion, lo tipico 
de "Esta seguro de querer comprar esta entrada, y a la hora de 
comprar las entradas debera reflejarse en la bbdd-->

<!--Tampoco se podra comprar mas de una entrada por compra-->

<?php
session_start();

// Parametros para la conexion a la base de datos
$servername = "localhost:3359";
$username = "root";
$password = "";
$dbname = "reto2_g4";

$conexion = new mysqli($servername, $username, $password, $dbname);

if ($conexion->connect_error) {
    die("Fallo en la conexión: " . $conn->connect_error);
}

// Cuando inicie sesion se mostrara aqui
if (isset($_SESSION['mensaje'])) {
// Para poder usarlo en Javascript
    $mensaje = $_SESSION['mensaje'];    
// Para que se elimine el mensaje cuando se cierre sesion
    unset($_SESSION['mensaje']);
} else {
    $mensaje = '';
}

//FALTA LA PARTE PARA QUE PILLE X COSA DE LA BASE DE DATOS :)

?>

<!DOCTYPE html>
    <html lang="es">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <link rel="stylesheet" href="styles.css" />
            <title>Cine Elorrieta-Errekamari</title>
    </head>
    <body>
        <header>
            <div class="contenedor_logo">
                <a href="index.php"><img class="Logotipo" src="header/image_logo.png" alt="Logotipo de Cine Elorrieta-Errekamari" /></a>
                <h1>CINE ELORRIETA</h1>
                <a href="logout.php">Cerrar Sesion</a>

<!----Se mostrara el mensaje "Hola, x"-->
            <?php 
        if(isset($_SESSION['cliente'])) { 
            echo "Hola " . $_SESSION['cliente']; 
        } 
    ?>

                <a href="login.html"><img class="usuario" src="header/personita.png" alt="Usuario de Cine Elorrieta-Errekamari"/></a>
            </div>
            <nav>
            <div class="contenedor_menu">
                <a href="index.php"> Películas</a>
                <a href="carrito.html"> Carrito</a>
            </div>
            </nav>
        </header>
        <main>

        </main>
        </body>
        <footer>
            
        </footer>
    </html>

