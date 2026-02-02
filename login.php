<?php
session_start();
$conexion = new mysqli("localhost:3359", "root", "", "reto2_g4");

if ($conexion->connect_error) die("Fallo: " . $conexion->connect_error);

$mensaje = $_SESSION['mensaje'] ?? '';
unset($_SESSION['mensaje']);

?>

<!DOCTYPE html>
    <html lang="es">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Login</title>
            <link rel="stylesheet" href="styles.css" />
            <script src="general.js" defer></script>
        </head>
        <body>
            <header>
                <div class="contenedor_logo">
                    <a href="index.php"><img class="logotipo" src="header/image_logo.png" alt="Logotipo" /></a>
                    <h1>CINE ELORRIETA</h1>
                    <a class="cerrar_sesion" href="logout.php">Cerrar Sesion</a>
                        <div class="saludo_usuario">
                            <?php if(isset($_SESSION['cliente'])) echo "Hola " . ($_SESSION['cliente']); ?>
                        </div>
                    <a href="login.php"><img class="usuario" src="header/personita.png" alt="Usuario"/></a>
                </div>
                <nav>
                    <div class="contenedor_menu">
                        <a href="index.php"> Películas</a>
                        <a href="carrito.php"> Carrito</a>
                    </div>
                </nav>
            </header>
                <main class="contenedor_mensaje">
                    <div class="mensaje-principal">
                    <h2 class="mensaje-titulo"> Login</h2>
                    <form id ="login" method="post" action="login2_0.php">
                        <label for="usuario"> Nombre de usuario: </label> <br/>
                        <input type="text" name="usuario" id="usuario" required> <br/><br/>
                        <label for="clave"> Clave: </label> <br/>
                        <input type="password" name="clave" id="clave" required/><br/> <br/>
                        <input  class="texto-boton" type="submit" value="Entrar"/>
                    </form>
                </div>
            </main>
        <footer>
            <div class="contenedor_footer">
                <img class="creative_commons" src="footer/cc.png" alt="Imagen de Creative Commons"/>
                <div class="texto_contenedor_footer">
                    <p>© 2026 Elorrieta Cines — Todos los derechos reservados</p>
                </div>
                <div class="imagenes_contenedor_footer">
                    <a href="https://www.facebook.com/"><img class="imagenes_redessociales"src="footer/facebook.png" alt="Imagen del icono de facebook"/></a>
                    <a href="https://www.instagram.com/"><img class="imagenes_redessociales" src="footer/instagram.png" alt="Imagen del icono de instagram"/></a>
                    <a href="https://www.x.com/"><img class="imagenes_redessociales" src="footer/twitter.png" alt="Imagen del icono de twitter"/></a>
                </div>
            </div>
        </footer>
        </body>
    </html>