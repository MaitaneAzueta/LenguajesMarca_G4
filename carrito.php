<?php
session_start();

$servername = "localhost:3359";
$username = "root";
$password = "";
$dbname = "reto2_g4";

$conexion = new mysqli($servername, $username, $password, $dbname);

if ($conexion->connect_error) {
    die("Conexión fallida: " . $conexion->connect_error);
}

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
            <script src="general.js" defer></script>
            <title>Carrito - Cine Elorrieta</title>
        </head>
        <body>
            <header>
                <div class="contenedor_logo">
                    <a href="index.php"><img class="logotipo" src="header/image_logo.png" alt="Logotipo" /></a>
                    <h1>CINE ELORRIETA</h1>
                    <a class="cerrar_sesion" href="logout.php">Cerrar Sesion</a>
                    <a href="login.html"><img class="usuario" src="header/personita.png" alt="Usuario"/></a>
                </div>

                <div class="saludo_usuario">
                    <?php if(isset($_SESSION['cliente'])) echo "Hola " . htmlspecialchars($_SESSION['cliente']); ?>
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
                    <?php if (!$datosCompra) { ?>
                        <section class="carrito-vacio">
                            <p>El carrito está vacío.</p>
                            <a href="index.php">Volver a la cartelera</a>
                        </section>
                    <?php } else { ?>
                    <section class="detalle-carrito">
                        <img src="<?= htmlspecialchars($datosCompra['Portada']) ?>" alt="Portada" style="width:200px;"/>
            
                        <div class="info-ticket">
                            <p><strong>Usuario:</strong> <?php echo isset($_SESSION['cliente']) ? htmlspecialchars($_SESSION['cliente']) : 'Invitado'; ?></p>
                            <p><strong>Cine:</strong> Cine Elorrieta-Errekamari</p>
                            <p><strong>Película:</strong> <?php echo htmlspecialchars($datosCompra['NomPelicula']); ?></p>
                            <p><strong>Sala:</strong> <?php echo htmlspecialchars($datosCompra['NomSala']); ?></p>
                            <p><strong>Fecha y Hora Inicio:</strong> <?php echo htmlspecialchars($datosCompra['FecHoraIni']); ?></p>
                            <P><strong>Fecha y Hora Fin:</strong> <?php echo htmlspecialchars($datosCompra['FecHoraFin']); ?></p>
                            <p><strong>Precio:</strong> €<?php echo number_format($datosCompra['Precio'], 2); ?></p>
                        </div>

                        <form action="finalizar_compra.php" method="POST">
                            <button type="submit" name="accion" value="confirmar">Confirmar</button>
                            <button type="submit" name="accion" value="cancelar">Cancelar</button>
                        </form>
                    </section>
                    <?php } ?>
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