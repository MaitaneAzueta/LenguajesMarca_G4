<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>HT - Registro</title>
    <link rel="stylesheet" href="styles.css">
    <script src="general.js"></script>
</head>
<body class="scan"> 
    <main class="contenedor_main">
        <div class="contenedor_login">
            <h2 style="text-align:center; color:#fff; letter-spacing:2px;">REGISTRO <span class="texto-azul">HT</span></h2>
            <form onsubmit="event.preventDefault(); registrarUsuario();" style="margin-top:30px;">
                <label>ID USUARIO:</label>
                <input type="text" id="reg_usuario" required>
                <label>CLAVE:</label>
                <input type="password" id="reg_clave" required>
                <button type="submit" class="texto-boton">CREAR CUENTA</button>
            </form>
            <p style="text-align:center; margin-top:20px;"><a href="login.php" class="texto-azul" style="text-decoration:none; font-size:0.7rem;">VOLVER AL LOGIN</a></p>
        </div>
    </main>
</body>
</html>