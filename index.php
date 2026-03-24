<?php session_start(); ?>
<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Hispania Travel - Inicio</title>
        <link rel="stylesheet" href="styles.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
        <script src="general.js"></script>
    </head>
    <body class="scan">
        
        <header>
            <div class="contenedor_logo">
                <img class="logo-hispania" src="Imagenes _Cabecera/logo.png" alt="Logo Hispania Travel">
                <h1 class="texto-azul">HISPANIA <span class="texto-rojo">TRAVEL S.A</span></h1>
                <div id="saludo-trucker" class="texto-azul">CONEXIÓN: INVITADO</div>
                <a href="login.php" id="btn-sesion" class="btn-rojo">ACCESO STAFF</a>
            </div>
            
            <nav>
                <a href="index.php">MENÚ PRINCIPAL</a>
                <a href="manuales.php" onclick="verificarAcceso(event, 'Manuales')">MANUALES HT</a>
                <a href="contacto.php">CONTACTO</a>
            </nav>
        </header>

        <main class="contenedor-principal">
            
            <section id="vista-publica">
                <div class="intro-empresa">
                    <h2 class="texto-rojo">BIENVENIDO A LA TERMINAL DE HISPANIA TRAVEL</h2>
                    <p class="texto-azul">[ ESPERANDO CARGA DE CONTENIDO CORPORATIVO... ]</p>
                </div>
            </section>

            <section id="vista-cliente" style="display:none;">
                </section>

            <section id="vista-admin" style="display:none;">
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
                    <a href="https://discord.gg/sTGRwdSWZJ" class="icono-social"><img src="Imagenes_Footer/discord.png" alt="Discord"></a>
                    <a href="#" class="icono-social"><img src="Imagenes_Footer/instagram.png" alt="Instagram"></a>
                    <a href="https://www.tiktok.com/@hispania_travel?_r=1&_t=ZN-94uf0aFnHl8" class="icono-social"><img src="Imagenes_Footer/tiktok.png" alt="TikTok"></a>
                    <a href="https://m.twitch.tv/hispaniatravel_sa/home" class="icono-social"><img src="Imagenes_Footer/twitch.png" alt="Twitch"></a>
                </div>
            </div>
        </footer>

    </body>
</html>