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
    die("Fallo: " . $conexion->connect_error);
}

$sql = "SELECT p.IDPelicula, p.NomPelicula, p.Portada, p.Duracion, p.DesPelicula,
               g.NomGenero, s.NomSala, ses.IDSesion, ses.FecHoraIni, ses.FecHoraFin, s.Aforo, ses.Precio
        FROM pelicula p
        INNER JOIN genero g ON p.IDGenero = g.IDGenero
        INNER JOIN sesion ses ON p.IDPelicula = ses.IDPelicula
        INNER JOIN sala s ON ses.IDSala = s.IDSala
        ORDER BY p.IDPelicula";

$resultado = $conexion->query($sql);
?>

<!DOCTYPE html>
    <html lang="es">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <link rel="stylesheet" href="styles.css">
            <script src="general.js"></script>
            <title>Cine Elorrieta-Errekamari</title>
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
            <main>
                <h2>Nuestras Peliculas</h2>
                <?php
                $idPeliAnt = null;
                if ($resultado && $resultado->num_rows > 0) {
                    echo '<div class="contenedor_peliculas">';

                    while($peli = $resultado->fetch_assoc()) {
                        if ($peli['IDPelicula'] !== $idPeliAnt) {
                            // NUEVA PELICULA

                            if ($idPeliAnt !== null) {
                                // Cierro el div de sesiones y luego el div de sesiones_peliculas
                                echo "</div>";
                            } 

                            $h = floor($peli['Duracion'] / 60);
                            $m = $peli['Duracion'] % 60;
                    
                            if ($h > 0 && $m > 0) {
                                $tiempo = "$h h y $m min";
                            } else if ($h > 0) {
                                $tiempo = "$h h";
                            } else {
                                $tiempo = "$m min";
                            }
                            
                            $idPeliAnt = $peli['IDPelicula'];
                            
                            echo 
                            '<div class="detalle_pelicula">
                                    <div class="imagenes_texto_pelicula">
                                        <img src="' . $peli['Portada'] . '" alt="Portada" />
                                    </div>

                                    <div class="contenedor_pelicula">
                                        <h3>' . $peli['NomPelicula'] . '</h3>
                                        <p class="texto_descripcion"><strong>Descripcion:</strong> ' . $peli['DesPelicula'] . '</p>
                                        <div class="duracion_genero">
                                            <p><strong>Género:</strong> ' . $peli['NomGenero'] . '</p>
                                            <p><strong>Duración:</strong> ' . $tiempo . '</p>
                                        </div>
                                    </div>
                            </div>';

                            echo '<div class="sesiones_peliculas">';  // ojo con este cierre

                        }

                        $idPeliAnt = $peli['IDPelicula'];
                        echo 
                            '<a><div class="salas"> 
                                <h4><strong>' . $peli['NomSala'] . '</strong></h4>
                                <p>Fecha de Inicio: ' . $peli['FecHoraIni'] . '</p>
                                <p>Fecha de Fin: ' . $peli['FecHoraFin'] . '</p>
                                <p class="precio">Precio: ' .  $peli['Precio'] . '€</p>
                                <p>Entradas: <span id="stock' . $peli['IDSesion'] . '">' . $peli['Aforo'] . '</span></p>';
                        if ($peli['Aforo'] > 0) { 
                            echo ' <button type="button" onclick="comprarUna(' . $peli['IDSesion'] . ')">Comprar</button>';
                        }
                        echo '</div></a>'; //cierre de las salas
                    }
                }
                echo '</div> <!-- Cierro el contenedor de peliculas -->';
            $conexion->close();
            ?> 
                </div> <!-- Sesiones peliculas -->
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