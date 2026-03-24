<?php session_start(); ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hispania Travel - Manuales de Staff</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="general.js"></script>
</head>
<body class="scan">
    
    <header class="border-azul">
        <div class="contenedor_logo">
            <img class="logo-hispania" src="Imagenes _Cabecera/logo.png" alt="Logo Hispania Travel">
            <h1 class="texto-azul">HISPANIA <span class="texto-rojo">TRAVEL S.A</span></h1>
            <div id="saludo-trucker" class="texto-azul">CONEXIÓN: STAFF</div>
            <a href="#" id="btn-sesion" class="btn-rojo" onclick="cerrarSesion()">CERRAR SESIÓN</a>
        </div>
        
        <nav>
            <a href="index.php">MENÚ PRINCIPAL</a>
            <a href="manuales.php" class="active">MANUALES HT</a>
            <a href="contacto.php">CONTACTO</a>
        </nav>
    </header>

    <main class="contenedor-principal">
        <h2 class="texto-rojo underline" style="text-align: center; margin-bottom: 30px;">CENTRO DE CAPACITACIÓN Y DESCARGAS</h2>

        <section class="seccion-manuales">
            <div class="card-trabajo">
                <h3 class="texto-azul"><i class="fas fa-download"></i> 01. DESCARGA DE RECURSOS</h3>
                <p>Acceso a los paquetes oficiales de Hispania Travel (Mods, Skins y Telemetría).</p>
                <div class="contenido-tutorial">
                    <p class="texto-rojo">[ CONTENIDO EN PROCESO DE CARGA... ]</p>
                </div>
            </div>

            <div class="card-trabajo">
                <h3 class="texto-azul"><i class="fas fa-tools"></i> 02. CONFIGURACIÓN DEL PERFIL</h3>
                <p>Instrucciones para sincronizar tu cuenta de ETS2 con el sistema logístico de la empresa.</p>
                <div class="contenido-tutorial">
                    <p class="texto-rojo">[ ESPERANDO INSTRUCCIONES DE INSTALACIÓN... ]</p>
                </div>
            </div>

            <div class="card-trabajo">
                <h3 class="texto-azul"><i class="fas fa-book"></i> 03. NORMATIVA DE CONDUCCIÓN</h3>
                <p>Reglamento interno para convoyes y transporte de mercancía pesada.</p>
                <div class="contenido-tutorial">
                    <p class="texto-rojo">[ DOCUMENTACIÓN RESTRINGIDA ]</p>
                </div>
            </div>
        </section>
    </main>

    <footer class="footer-hispania">
        <div class="footer-contenedor">
            <div class="footer-izquierda">
                <img src="Imagenes_Footer/logo2.png" alt="HT Logo Footer" class="img-footer">
            </div>
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

    <script>
        window.onload = function() {
            if (!localStorage.getItem("ht_user")) {
                alert("¡ACCESO RESTRINGIDO! Solo personal autorizado de Hispania Travel.");
                window.location.href = "login.php";
            }
        };
    </script>
</body>
</html>