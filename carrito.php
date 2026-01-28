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
    $sql = "SELECT p.NomPelicula, s.NomSala, ses.FecHoraIni
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
    <link rel="stylesheet" href="styles.css" />
    <title>Cine Elorrieta</title>
</head>
<body>
    <header>
        <h1>CINE ELORRIETA</h1>
        <nav>
            <a href="index.php">Películas</a>
            <a href="carrito.php">Carrito</a>
        </nav>
    </header>
    <main>
        <h2>Resumen de Entrada</h2>
        <?php if ($datosCompra !== null) { ?>
            <section class="detalle-carrito">
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
</body>
</html>