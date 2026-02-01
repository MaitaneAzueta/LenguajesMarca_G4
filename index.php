<?php
session_start();
$conexion = new mysqli("localhost:3359", "root", "", "reto2_g4");

if ($conexion->connect_error) die("Fallo: " . $conexion->connect_error);

$mensaje = $_SESSION['mensaje'] ?? '';
unset($_SESSION['mensaje']);

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
                            <?php if(isset($_SESSION['cliente'])) echo "Hola " . htmlspecialchars($_SESSION['cliente']); ?>
                        </div>
                    <a href="login.html"><img class="usuario" src="header/personita.png" alt="Usuario"/></a>
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
                    while($peli = $resultado->fetch_assoc()) {
                    if ($peli['IDPelicula'] !== $idPeliAnt) {
                        if ($idPeliAnt !== null) echo "</div></div>"; // Cierra sesiones y contenedor anterior

                            $h = floor($peli['Duracion'] / 60);
                            $m = $peli['Duracion'] % 60;
                    
                            if ($h > 0 && $m > 0) {
                                $tiempo = "$h h y $m min";
                            } else if ($h > 0) {
                                $tiempo = "$h h";
                            } else {
                                $tiempo = "$m min";
                            }
                ?>
                <div class="contenedor_peliculas">
                    <div class="detalle_pelicula">
                        <div class="imagenes_texto_pelicula">
                            <img src="<?= $peli['Portada'] ?>" alt="Portada" />
                        </div>

                    <div class="contenedor_pelicula">
                        <h3><?= $peli['NomPelicula'] ?></h3>
                        <p class="texto_descripcion"><strong>Descripcion:</strong> <?= $peli['DesPelicula'] ?></p>
                            <div class="duracion_genero">
                                <p><strong>Género:</strong> <?= $peli['NomGenero'] ?> </p>
                                <p><strong>Duración:</strong> <?= $tiempo ?></p>
                            </div>
                    </div>
                </div>
                <div class="sesiones_peliculas"> 
                <?php 
                } 
                    $idPeliAnt = $peli['IDPelicula'];
                ?>
            
                <div class="salas"> 
                    <h4><strong><?= $peli['NomSala'] ?></strong></h4>
                    <p>Fecha de Inicio: <?= $peli['FecHoraIni'] ?></p>
                    <p>Fecha de Fin: <?= $peli['FecHoraFin'] ?></p>
                    <p class="precio">Precio: <?= $peli['Precio'] ?>€</p>
                    <p>Entradas: <span id="stock<?= $peli['IDSesion'] ?>"><?= $peli['Aforo'] ?></span></p>
                    <?php if ($peli['Aforo'] > 0) { ?>
                        <button type="button" onclick="comprarUna(<?= $peli['IDSesion'] ?>)">Comprar</button>
                    <?php } ?>
                </div>
                
                <?php 
                } 
                } else {
                    echo "<p> No hay peliculas disponibles </p>";
                }
            $conexion->close();
            ?> 

            </main>

        <footer>
            <div class="contenedor_footer">
                <img class="creative_commons" src="footer/cc.png" alt="CC"/>
                <div class="texto_contenedor_footer">
                    <p>© 2026 Elorrieta Cines — Todos los derechos reservados</p>
                </div>
                <div class="imagenes_contenedor_footer">
                    <a href="https://facebook.com"><img class="imagenes_redessociales" src="footer/facebook.png" alt="FB"/></a>
                    <a href="https://instagram.com"><img class="imagenes_redessociales" src="footer/instagram.png" alt="IG"/></a>
                    <a href="https://x.com"><img class="imagenes_redessociales" src="footer/twitter.png" alt="X"/></a>
                </div>
            </div>
        </footer>
        </body>
    </html>