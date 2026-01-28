<!--En la informacion de compra debera aparecer numero de entradas, 
sala, pelicula, fecha y hora-->

<!--Aparte debera de tener un apartado de confirmacion, lo tipico 
de "Esta seguro de querer comprar esta entrada, y a la hora de 
comprar las entradas debera reflejarse en la bbdd-->

<!--Tampoco se podra comprar mas de una entrada por compra-->

<?php
session_start();

$servername = "localhost:3359";
$username = "root";
$password = "";
$dbname = "reto2_g4";

$conexion = new mysqli($servername, $username, $password, $dbname);

$idSesion = null;
if (isset($_SESSION['ultima_sesion_comprada'])) {
    $idSesion = $_SESSION['ultima_sesion_comprada'];
}

$datosCompra = null;
if ($idSesion !== null) {
    $sql = "SELECT p.NomPelicula, ses.Precio, s.NomSala, ses.FecHoraIni, p.Portada
        FROM sesion ses
        INNER JOIN pelicula p ON p.IDPelicula = ses.IDPelicula
        INNER JOIN sala s ON ses.IDSala = s.IDSala
        WHERE ses.IDSesion = $idSesion";

    $resultado = $conexion->query($sql);
    if ($resultado) {
        if ($resultado->num_rows > 0) {
            $datosCompra = $resultado->fetch_assoc();
        }
    }
}
?>

<!DOCTYPE html>
    <html lang="es">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <link rel="stylesheet" href="styles.css" />
            <script type="text/javascript" src="general.js"></script>
            <title>Cine Elorrieta-Errekamari</title>
    </head>
    <body>
        <header>
            <div class="contenedor_logo">
                <a href="index.php"><img class="Logotipo" src="header/image_logo.png" alt="Logotipo de Cine Elorrieta-Errekamari" /></a>
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
                <a href="carrito.php"> Carrito</a>
            </div>
            </nav>
        </header>
        <main>
        <h2>Resumen de Entrada</h2>
        <?php if ($datosCompra !== null) { ?>
            <section class="detalle-carrito">
                <img src="<?php echo $peli['Portada']; ?>" alt="Portada de peliculas" />
                <p><strong>Usuario:</strong> <?php echo isset($_SESSION['cliente']) ? $_SESSION['cliente'] : 'Julio'; ?></p>
                <p><strong>Cine:</strong> Cine Elorrieta-Errekamari</p>
                <p><strong>Película:</strong> <?php echo $datosCompra['NomPelicula']; ?></p>
                <p><strong>Sala:</strong> <?php echo $datosCompra['NomSala']; ?></p>
                <p><strong>Fecha y Hora:</strong> <?php echo $datosCompra['FecHoraIni']; ?></p>
            </section>
        <?php } else { ?>
            <p>No hay entrada seleccionada. ID detectado: <?php echo $idSesion; ?></p>
            <p>Asegúrate de que el botón en index.php envía el IDSesion correcto.</p>
            <a href="index.php">Volver a la cartelera</a>
        <?php } ?>
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