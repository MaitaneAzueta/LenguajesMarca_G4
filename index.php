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

$sql = "SELECT p.IDPelicula, p.NomPelicula, p.Duracion, p.Portada, g.NomGenero, p.DesPelicula, 
s.NomSala, s.Aforo,
ses.FecHoraIni, ses.FecHoraFin, ses.Precio
FROM pelicula p
INNER JOIN genero g ON p.IDGenero = g.IDGenero
INNER JOIN sesion ses ON p.IDPelicula = ses.IDPelicula
INNER JOIN sala s ON ses.IDSala = s.IDSala
ORDER BY p.IDPelicula, ses.FecHoraIni";
$resultado = $conexion->query($sql);
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
                <img class="Logotipo" src="header/image_logo.png" alt="Logotipo de Cine Elorrieta-Errekamari" />
                <h1>CINE ELORRIETA</h1>
                <a href="logout.php">Cerrar Sesion</a>

<!----Se mostrara el mensaje "Hola, x-->
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
            <h2>Cine</h2>

            <?php
                $idPeliculaAnterior = null;
                if ($resultado && $resultado->num_rows > 0) {
                    while($peli = $resultado->fetch_assoc()) {
                        if ($peli['IDPelicula'] !== $idPeliculaAnterior) {
                            if ($idPeliculaAnterior !== null) {
                                echo "</div></section>";
                            }

                        $horas = floor($peli['Duracion'] / 60);
                        $minutos = $peli['Duracion'] % 60;
                
                        if ($horas > 0 && $minutos > 0) {
                            $tiempo = $horas . " hora/s y " . $minutos . " minutos";
                        } else if ($horas > 0 && $minutos == 0) {
                            $tiempo = $horas . " hora/s";
                        } else {
                            $tiempo = $minutos . " minutos";
                        }

            ?>
            <section class="pelicula">
                <img src="<?php echo $peli['Portada']; ?>" alt="Portada de peliculas" />
                <h3><?php echo $peli['NomPelicula']; ?></h3>
                <p><strong>Descripcion:</strong> <?php echo $peli['DesPelicula']; ?></p>
                <p><strong>Género:</strong> <?php echo $peli['NomGenero']; ?></p>
                <p><strong>Duración:</strong> <?php echo $tiempo; ?></p>
                <div class="contenedor-sesiones"> <?php
            } 
            ?>
            <hr>
            <div class="sesion">
                <h4><strong><?php echo $peli['NomSala'] ?></strong></h4>
                <p>Fecha de Inicio: <?php echo $peli['FecHoraIni'] ?></p>
                <p>Fecha de Fin: <?php echo $peli['FecHoraFin'] ?></p>
                <p>Precio: <?php echo $peli['Precio'] ?>€</p>
                <p><?php echo $peli['Aforo'] ?> Entradas Disponibles</p>
            </div>

            <?php
                $idPeliculaAnterior = $peli['IDPelicula'];
                }
                    echo "</div></section>";
                } else {
                    echo "<p> No hay peliculas disponibles </p>";
                }
            $conexion->close();
            ?>

        </main>
        </body>
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
    </html>
