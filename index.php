<?php session_start(); ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Hispania Travel - Inicio</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="general.js"></script>
</head>
<body class="scan">
    <header>
        <div class="contenedor_logo">
            <div style="display: flex; align-items: center; gap: 15px;">
                <img class="logo-hispania" src="Imagenes _Cabecera/logo.png" alt="Logo">
                <h1 class="texto-azul">HISPANIA <span class="texto-rojo">TRAVEL S.A</span></h1>
            </div>
            <div id="saludo-trucker" class="texto-azul">CONEXIÓN: INVITADO</div>
            <div style="display: flex; gap: 15px; align-items: center;">
                <a href="login.php" id="btn-sesion" class="btn-rojo">ACCESO STAFF</a>
                <a href="registro.php" id="btn-registro-header" class="texto-azul" style="text-decoration:none; font-weight:bold; font-size:0.8rem;">REGISTRO</a>
            </div>
        </div>
        <nav id="menu-navegacion">
            <a href="index.php">MENÚ PRINCIPAL</a>
            <a href="manuales.php" id="nav-manuales">MANUALES HT</a>
            <a href="contacto.php">CONTACTO</a>
        </nav>
    </header>

    <main class="contenedor-principal">
        <div class="card-trabajo">
            <h2 class="texto-rojo">BIENVENIDO A LA TERMINAL</h2>
            <p>Esperando carga de contenido corporativo...</p>
        </div>
    </main>

    <footer class="footer-hispania">
        <div class="footer-contenedor">
            <img src="Imagenes_Footer/logo2.png" class="img-footer">
            <div class="footer-centro">
                <p>© 2026 HISPANIA TRAVEL S.A. - TODOS LOS DERECHOS RESERVADOS</p>
                <p class="texto-rojo">CREADORA: <span class="autor">ARIMA</span></p>
            </div>
            <div class="footer-derecha">
                <a href="#" class="icono-social"><i class="fab fa-discord"></i> DISCORD</a>
                <a href="#" class="icono-social"><i class="fab fa-instagram"></i> INSTAGRAM</a>
                <a href="#" class="icono-social"><i class="fab fa-tiktok"></i> TIKTOK</a>
            </div>
        </div>
    </footer>
</body>
</html>