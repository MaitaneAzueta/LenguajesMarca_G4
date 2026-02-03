<?php
// Iniciar una sesion de usuario para almacenar los datos.
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
    die("Fallo de conexión: " . $conexion->connect_error);
}

// Recuperar un posible mensaje de error o éxito guardado en la sesión (operador null coalescing)
$mensaje = $_SESSION['mensaje'] ?? '';

// Borrar el mensaje de la sesión para que no se vuelva a mostrar al recargar la página
unset($_SESSION['mensaje']);
?>

<!DOCTYPE html>
    <html lang="es">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
<!--Enlace a la hoja de estilos y al archivo js general-->
            <link rel="stylesheet" href="styles.css">
            <script src="general.js"></script>
            <title>Cine Elorrieta-Errekamari</title>
        </head>
        <body>
<!--Empieza el encabezado de nuestra pagina-->
            <header>
<!--Contenedor principal del encabezado, logotipo, título y usuario-->
                <div class="contenedor_logo">
                    <a href="index.php"><img class="logotipo" src="header/image_logo.png" alt="Logotipo" /></a>
                    <h1>CINE ELORRIETA</h1>
                    <a class="cerrar_sesion" href="logout.php" onclick="return mensaje_cerrarSesion();">Cerrar Sesion</a>
<!--Contenedor para el usuario que inicie sesion-->
                        <div class="saludo_usuario">
<!--Aparece un mensaje de bienvenida para el usuario-->
                            <?php if(isset($_SESSION['cliente'])) echo "Hola " . ($_SESSION['cliente']); ?>
                        </div> <!--Se cierra el div de saludo usuario-->
                    <a href="login.php"><img class="usuario" src="header/personita.png" alt="Usuario"/></a>
                </div> <!--Se cierra el div de contenedor-logo (almacena logotipo, título y usuario)-->
                <nav>
<!--Contendor que almacena los enlaces del menú principal-->
                    <div class="contenedor_menu">
                        <a href="index.php"> Películas</a>
                        <a href="carrito.php"> Carrito</a>
                    </div> <!--Se cierra el div de contenedor_menu-->
                </nav>
            </header>
            <main class="contenedor_mensaje">
                <?php
// El contenedor principal del login, con el titulo y el formulario
                echo '<div class="mensaje-principal">
                    <h2 class="mensaje-titulo"> Login</h2>
                    <form id="login" method="post" action="login2_0.php">
                        <label for="usuario"> Nombre de usuario: </label> <br/>
                        <input type="text" name="usuario" id="usuario" required> <br/><br/>
                        <label for="clave"> Clave: </label> <br/>
                        <input type="password" name="clave" id="clave" required/><br/> <br/>
                        <input class="texto-boton" type="submit" value="Entrar"/>
                    </form>
                </div>'; // Cierre del contenedor principal del login
// Mostrar el mensaje de error o de "Hola, x" si existe
                if ($mensaje !== '') {
                    echo '<p class="mensaje-error">' . $mensaje . '</p>';
                }
//Cierre de la conexion php
                $conexion->close();
                ?> 
            </main>
        <footer>
<!--Contenedor principal del footer, imagen, derechos reservados y redes sociales-->
            <div class="contenedor_footer">
                <img class="creative_commons" src="footer/cc.png" alt="CC"/>
<!--Contenedor que almacena el texto del footer-->
                <div class="texto_contenedor_footer">
                    <p>© 2026 Elorrieta Cines — Todos los derechos reservados</p>
                </div> <!--Cierre del contenedor del texto del footer-->
<!--Contenedor que almacena los logos de las imagenes de las redes sociales-->
                <div class="imagenes_contenedor_footer">
                    <a href="https://facebook.com"><img class="imagenes_redessociales" src="footer/facebook.png" alt="FB"/></a>
                    <a href="https://instagram.com"><img class="imagenes_redessociales" src="footer/instagram.png" alt="IG"/></a>
                    <a href="https://x.com"><img class="imagenes_redessociales" src="footer/twitter.png" alt="X"/></a>
                </div> <!--Cierre del contenedor de las imagenes de las redes sociales-->
            </div> <!--Cierre del contenedor principal del footer-->
        </footer>
        </body>
    </html>