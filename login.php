<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Hispania Travel - Acceso de Personal</title>
        <link rel="stylesheet" href="styles.css">
        <script src="general.js"></script>
</head>
<body class="scan"> 

    <main class="contenedor_main">
        <div class="contenedor_login">
            <h2 class="login-titulo">HISPANIA <span style="color:#0000FF">SYSTEMS</span></h2>
            
            <p style="color:#FF0000; text-align:center; font-size:0.7rem; margin-bottom:20px; text-transform: uppercase; letter-spacing: 1px;">
                PROPIEDAD EXCLUSIVA DE HISPANIA TRAVEL S.A.
            </p>
            
            <form id="loginForm" onsubmit="event.preventDefault(); validarAcceso();">
                
                <label for="usuario_input">ID EMPLEADO:</label>
                <input type="text" id="usuario_input" placeholder="ID de Staff" required autocomplete="username">
                
                <label for="clave_input">CLAVE DE RUTA:</label>
                <input type="password" id="clave_input" placeholder="••••" required autocomplete="current-password">
                
                <button type="submit" class="texto-boton">AUTENTICAR ACCESO</button>
                
            </form>

            <div style="margin-top: 25px; border-top: 1px solid rgba(0, 0, 255, 0.3); padding-top: 10px;">
                <p style="color: #0000FF; font-size: 0.6rem; text-align: center;">
                    SISTEMA DE SEGURIDAD VISTA 2.6 <br> CREADO POR ARIMA
                </p>
            </div>
        </div>
    </main>

</body>
</html>
