<?php
session_start();
// Cuando inicie sesion se mostrara aqui
if (isset($_SESSION['mensaje'])) {
    // Para poder usarlo en Javascript
    $mensaje = $_SESSION['mensaje'];    
    // Para que se elimine el mensaje cuando se cierre sesion
    unset($_SESSION['mensaje']);
} else {
    $mensaje = '';
}

$sql = "SELECT p.NomPelicula, p.Duracion, p.Portada, gNomGenero
FROM pelicula p
INNER JOIN genero g ON p.IDGenero = g.IDGenero";
$resultado = $conexion->query($sql);
?>

<!DOCTYPE html>
    <html lang="es">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Cine Elorrieta-Errekamari </title>

<!----Se mostrara el mensaje "Hola, x-->
            <?php 
        if(isset($_SESSION['cliente'])) { 
            echo " - Hola " . $_SESSION['cliente']; 
        } 
    ?>

    </head>
    <body>
        <header>
             <div class="contenedor_">
                <a href="Cine_Elorrieta-Errekamari.html"><img src="header/image_logo.png" alt="Logotipo de Cine Elorrieta-Errekamari" /></a>
                <h1>Cine Elorrieta-Errekamari</h1>
                <a href="login.html"><img src="header/personita.png" alt="Usuario de Cine Elorrieta-Errekamari"/></a>
            </div>
            <nav>
                <a href="peliculas.html">Peliculas</a>
                <a href="carrito.html">Carrito</a>
            </nav>
        </header>
            <main>
                <h1>Cine</h1>
                <?php
                    if ($resultado && $resultado->num_rows > 0){
                        while($peli = $resultado->fetch_assoc()){

                            $horas = floor($peli['Duracion']) / 60);
                            $minutos = $peli['Duracion'] % 60;
                            if ($horas > 0 && $minutos > 0) {
                                $tiempo = $horas . " hora/s y " . $minutos . " minutos";
                            } else if ($horas > 0 && $minutos == 0) {
                                $tiempo = $horas . " hora/s";
                            } else {
                                $tiempo = $minutos . "minutos";
                            }
                ?>
                <img src = "<?php echo $peli['Portada']; ?>" alt = Portada de peliculas />
                    <h2> <?php echo $peli['NomPelicula']; ?> </h2>
                    <p> <strong> Género: </strong> <?php echo $peli['NomGenero']; ?> </p>
                    <p> <strong> Duración: </strong> <?php echo $tiempo; ?> </p>
                
                <?php
                    }

                    } else {
                        echo "<p> No hay peliculas disponibles </p>";
                    }

                    conexion->close();
                    ?>    
            </main>
    </body>
</html>
