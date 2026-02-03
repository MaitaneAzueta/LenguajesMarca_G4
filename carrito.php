<?php
session_start();
$conexion = new mysqli("localhost:3359", "root", "", "reto2_g4");

if ($conexion->connect_error) die("Fallo: " . $conexion->connect_error);

$datosCompra = null;

if (isset($_SESSION['carrito_sesion'])) {
    $idSesion = $_SESSION['carrito_sesion'];
    
    $sql = "SELECT p.NomPelicula, ses.Precio, s.NomSala, ses.FecHoraIni, ses.FecHoraFin, p.Portada
            FROM sesion ses
            INNER JOIN pelicula p ON p.IDPelicula = ses.IDPelicula
            INNER JOIN sala s ON ses.IDSala = s.IDSala
            WHERE ses.IDSesion = $idSesion";

    $resultado = $conexion->query($sql);

    if ($resultado && $resultado->num_rows > 0) {
        $datosCompra = $resultado->fetch_assoc();
    }
}
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
                <h2>Resumen de la compra</h2>
                <?php
                if (!$datosCompra) {
                    echo '<div class="contenedor_peliculas">
                            <p>El carrito está vacío.</p>
                            <a href="index.php">Volver a la cartelera</a>
                          </div>';
                } else {
                    echo '<div class="contenedor_peliculas">';

                    echo 
                    '<div class="detalle_pelicula">
                            <div class="imagenes_texto_pelicula">
                                <img src="' . $datosCompra['Portada'] . '" alt="Portada" />
                            </div>

                            <div class="contenedor_pelicula">
                                <h3>' . $datosCompra['NomPelicula'] . '</h3>
                                <p><strong>Sala:</strong> ' . $datosCompra['NomSala'] . '</p>
                                <div class="duracion_genero">
                                    <p><strong>Inicio:</strong> ' . $datosCompra['FecHoraIni'] . '</p>
                                    <p><strong>Fin:</strong> ' . $datosCompra['FecHoraFin'] . '</p>
                                </div>
                            </div>
                    </div>';

                    echo '<div class="sesiones_peliculas">';
                    
                    echo 
                        '<a><div class="salas"> 
                                <h4><strong>Ticket Detalle</strong></h4>
                                <p>Usuario: ' . (isset($_SESSION['cliente']) ? $_SESSION['cliente'] : 'Invitado') . '</p>
                                <p class="precio">Total: ' .  $datosCompra['Precio'] . '€</p>
                                <form action="finalizar_compra.php" method="POST">
                                    <button type="submit" name="accion" value="confirmar">Confirmar</button>
                                    <button type="submit" name="accion" value="cancelar">Cancelar</button>
                                </form>
                        </div></a> ';
                    
                    echo '</div> ';
                    echo '</div> ';
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