<?php
// Iniciar una sesion de usuario para almacenar los datos.
session_start();

// Establecer conexión con la base de datos MySQL (host:puerto, usuario, contraseña, nombre_bd)
$conexion = new mysqli("localhost:3359", "root", "", "reto2_g4");

// Comprobar si la conexión falló y detener la ejecución en caso de error
if ($conexion->connect_error) {
    die("Fallo: " . $conexion->connect_error);
}

// Inicializar la variable que contendrá la información de la compra como nula
$datosCompra = null;

// Comprueba si hay alguna sesión de carrito activa, en ese usuario
if (isset($_SESSION['carrito_sesion'])) {
    
// Guardar el ID de la sesión en una variable
    $idSesion = $_SESSION['carrito_sesion'];
    
//Preparar la consulta SQL (Query):
    $sql = "SELECT p.NomPelicula, ses.Precio, s.NomSala, ses.FecHoraIni, ses.FecHoraFin, p.Portada
            FROM sesion ses
            INNER JOIN pelicula p ON p.IDPelicula = ses.IDPelicula
            INNER JOIN sala s ON ses.IDSala = s.IDSala
            WHERE ses.IDSesion = $idSesion";

// Ejecutar la consulta en la base de datos
    $resultado = $conexion->query($sql);

// Comprobar si la consulta se ejecuto y si devolvió al menos una fila (registro)
    if ($resultado && $resultado->num_rows > 0) {
// Extraer los datos del resultado y guardarlos en un array
        $datosCompra = $resultado->fetch_assoc();
    }
}
// El script termina aquí; los datos ahora están listos en $datosCompra para usarse en el HTML
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
// Si no hay datos de compra, mostramos aviso de carrito vacío
            if (!$datosCompra) {
                echo
// Se abre un section para almacenar los datos del carrito vacío
                '<section class="carrito-vacio">
                    <p>El carrito está vacío.</p>
                    <a href="index.php">Volver a la cartelera</a>
                </section>'; // Cierre del section carrito-vacio
            } else {
// Si hay datos, imprimimos el ticket de compra y se habre otro section para almacenar los datos del carrito con contenido
                echo '<section>'
// Inicio del contenedor principal de carrito.php, detalle-carrito
                        '<div class="detalle-carrito">
                            <img src="' . htmlspecialchars($datosCompra['Portada']) . '" alt="Portada" style="width:200px;"/>'
// Contenedor de la informacion almacenada de la sesion seleccionada, info-ticket
                            '<div class="info-ticket">
                                <p><strong>Usuario:</strong> ' . (isset($_SESSION['cliente']) ? htmlspecialchars($_SESSION['cliente']) : 'Invitado') . '</p>
                                <p><strong>Cine:</strong> Cine Elorrieta-Errekamari</p>
                                <p><strong>Película:</strong> ' . $datosCompra['NomPelicula'] . '</p>
                                <p><strong>Sala:</strong> ' . $datosCompra['NomSala'] . '</p>
                                <p><strong>Fecha y Hora Inicio:</strong> ' . $datosCompra['FecHoraIni'] . '</p>
                                <p><strong>Fecha y Hora Fin:</strong> ' . $datosCompra['FecHoraFin'] . '</p>
                                <p><strong>Precio:</strong> €' . number_format($datosCompra['Precio'], 2) . '</p>
                            </div>' // Cieerre del contenedor info-ticket
// Formulario para confirmar o cancelar la compra
                            '<form action="finalizar_compra.php" onsubmit="return confirmar();" method="POST">
                                <button type="submit" name="accion" value="confirmar">Confirmar</button>
                                <button type="submit" name="accion" value="cancelar">Cancelar</button>
                            </form>
                        </div>' // Cierre del contenedor detalle-carrito
                    '</section>'; // Cierre del section carrito con contenido
            }
            
            // Cerramos la conexión a la base de datos para liberar recursos
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