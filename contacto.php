<?php session_start(); ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Hispania Travel - Atención al Cliente</title>
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
        <div class="seccion-contacto">
            <h2 class="texto-rojo underline" style="text-align: center; margin-bottom: 20px;">CENTRO DE COMUNICACIONES</h2>
            <div class="grid-contacto">
                <div class="formulario-container border-azul">
                    <form onsubmit="event.preventDefault(); alert('TRANSMISIÓN ENVIADA A CENTRAL. ESPERE RESPUESTA.'); this.reset();">
                        <label class="texto-azul">NOMBRE COMPLETO:</label>
                        <input type="text" placeholder="Escribe tu nombre..." required>
                        <label class="texto-azul">CORREO ELECTRÓNICO:</label>
                        <input type="email" placeholder="usuario@ejemplo.com" required>
                        <label class="texto-azul">ASUNTO:</label>
                        <select class="select-ht">
                            <option>Solicitud de Empleo (Chofer)</option>
                            <option>Presupuesto Logístico</option>
                            <option>Reporte de Incidencia</option>
                        </select>
                        <label class="texto-azul">MENSAJE:</label>
                        <textarea placeholder="Escribe tu mensaje aquí..." rows="5" required></textarea>
                        <button type="submit" class="btn-rojo">ENVIAR TRANSMISIÓN</button>
                    </form>
                </div>
                <div class="info-sedes">
                    <div class="card-trabajo">
                        <h3 class="texto-rojo">HORARIO DE OPERACIONES</h3>
                        <p>Lunes a Viernes: 08:00 - 20:00</p>
                        <p>Sábados: 09:00 - 14:00 (Convoyes Oficiales)</p>
                    </div>
                </div>
            </div>
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
