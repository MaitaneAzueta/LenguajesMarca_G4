<?php
// 1. Mostrar errores para saber qué falla (QUITA ESTO CUANDO FUNCIONE)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

// 2. Verificar si el usuario está logueado y tiene rango permitido
// Según tu captura, el rango se muestra como [Gestor]
// Asegúrate de que en la sesión se guarde exactamente 'Gestor'
if (!isset($_SESSION['rango']) || $_SESSION['rango'] == 'Visitante') {
    die("No tienes permiso para ver esta sección o no has iniciado sesión.");
}

// 3. Conexión a TiDB Cloud
$host = 'gateway01.eu-central-1.prod.aws.tidbcloud.com'; // EJ: gateway01.us-east-1.prod.aws.tidbcloud.com
$user = '2tvbKFgEYyWm58d.root'; // EJ: tu_usuario
$pass = 'TlDYiAeavwP9WiOO'; // EJ: tu_password
$db   = 'test';
$port = 4000;

$conn = mysqli_init();
// Ruta del certificado SSL necesaria para Render
$conn->ssl_set(NULL, NULL, "/etc/ssl/certs/ca-certificates.crt", NULL, NULL);

if (!$conn->real_connect($host, $user, $pass, $db, $port)) {
    die("Error de conexión a la base de datos: " . mysqli_connect_error());
}

$mi_rango = $_SESSION['rango'];
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Hispania Travel - Eventos</title>
    <style>
        body { background-color: #000; color: white; font-family: sans-serif; padding: 20px; }
        .panel-gestor { border: 2px solid #ff0000; padding: 20px; margin-bottom: 30px; background: #111; }
        .evento-card { border-bottom: 1px solid #333; padding: 15px; margin-bottom: 10px; }
        textarea { width: 100%; height: 80px; background: #222; color: #fff; border: 1px solid #444; }
        button { background: #ff0000; color: white; border: none; padding: 10px 20px; cursor: pointer; font-weight: bold; }
    </style>
</head>
<body>

    <h1>TERMINAL DE EVENTOS</h1>

    <?php if ($mi_rango === 'Gestor'): ?>
        <div class="panel-gestor">
            <h3>SUBIR NUEVO CONTENIDO</h3>
            <form id="uploadForm">
                <textarea name="texto" placeholder="Escribe aquí el texto del evento..." required></textarea><br><br>
                <input type="file" name="foto" accept="image/*" required><br><br>
                <button type="submit">PUBLICAR AHORA</button>
            </form>
            <p id="status" style="color: #00ff00;"></p>
        </div>
    <?php endif; ?>

    <div id="lista-eventos">
        <h2>Últimos Eventos</h2>
        <?php
        $res = $conn->query("SELECT * FROM publicaciones ORDER BY fecha_creacion DESC");
        if ($res && $res->num_rows > 0) {
            while ($row = $res->fetch_assoc()) {
                echo "<div class='evento-card'>";
                echo "<p>" . htmlspecialchars($row['texto']) . "</p>";
                echo "<img src='" . $row['imagen_url'] . "' style='max-width: 400px; border: 1px solid #333;'>";
                echo "<br><small>Publicado el: " . $row['fecha_creacion'] . "</small>";
                echo "</div>";
            }
        } else {
            echo "<p>No hay eventos publicados todavía.</p>";
        }
        ?>
    </div>

    <script>
    const form = document.getElementById('uploadForm');
    if (form) {
        form.onsubmit = async (e) => {
            e.preventDefault();
            const status = document.getElementById('status');
            status.innerText = "Procesando subida...";

            const formData = new FormData(e.target);
            try {
                const resp = await fetch('upload_logic.php', { method: 'POST', body: formData });
                const result = await resp.json();
                if(result.success) {
                    alert("¡Publicado!");
                    location.reload();
                } else {
                    status.innerText = "Error: " + result.error;
                }
            } catch (err) {
                status.innerText = "Error de conexión con el servidor.";
            }
        };
    }
    </script>
</body>
</html>