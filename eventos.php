<?php
// Conexión a TiDB Cloud
$host = 'gateway01.eu-central-1.prod.aws.tidbcloud.com';
$user = '2tvbKFgEYyWm58d.root';
$pass = 'TlDYiAeavwP9WiOO';
$db   = 'test';
$port = 4000;

$conn = mysqli_init();
$conn->ssl_set(NULL, NULL, "/etc/ssl/certs/ca-certificates.crt", NULL, NULL);
$conn->real_connect($host, $user, $pass, $db, $port);

// No usamos die() aquí para que la página cargue y el JS tome el control
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Hispania Travel - Eventos</title>
    <style>
        body { background-color: #000; color: white; font-family: sans-serif; padding: 20px; }
        .panel-gestor { border: 2px solid #ff0000; padding: 20px; margin-bottom: 30px; background: #111; display: none; }
        .evento-card { border: 1px solid #333; padding: 15px; margin-bottom: 20px; background: #0a0a0a; border-radius: 5px; }
        .evento-card img { max-width: 100%; height: auto; margin-top: 10px; border: 1px solid #444; }
        textarea { width: 100%; height: 80px; background: #222; color: #fff; border: 1px solid #444; padding: 10px; }
        button { background: #ff0000; color: white; border: none; padding: 10px 20px; cursor: pointer; font-weight: bold; }
    </style>
</head>
<body>

    <h1>TERMINAL DE EVENTOS</h1>

    <div id="panel-gestor" class="panel-gestor">
        <h3 style="color: #ff0000;">NUEVA ENTRADA DE SISTEMA</h3>
        <form id="uploadForm">
            <textarea name="texto" placeholder="Escribe el reporte o evento..." required></textarea><br><br>
            <input type="file" name="foto" accept="image/*" required><br><br>
            <button type="submit">SUBIR A LA RED</button>
        </form>
        <p id="status" style="color: #00ff00;"></p>
    </div>

    <div id="lista-eventos">
        <?php
        $res = $conn->query("SELECT * FROM publicaciones ORDER BY fecha_creacion DESC");
        if ($res && $res->num_rows > 0) {
            while ($row = $res->fetch_assoc()) {
                echo "<div class='evento-card'>";
                echo "  <p>" . htmlspecialchars($row['texto']) . "</p>";
                echo "  <img src='" . $row['imagen_url'] . "'>";
                echo "  <br><small style='color:#666;'>Fecha: " . $row['fecha_creacion'] . "</small>";
                echo "</div>";
            }
        } else {
            echo "<p>No hay eventos registrados.</p>";
        }
        ?>
    </div>

    <script>
        // Sincronizamos con tu sistema de localStorage
        const userRole = localStorage.getItem("ht_role");
        const userName = localStorage.getItem("ht_user");

        // Si es Gestor, mostramos el panel
        if (userRole === "Gestor") {
            document.getElementById('panel-gestor').style.display = 'block';
        }

        // Manejo del formulario
        const form = document.getElementById('uploadForm');
        if (form) {
            form.onsubmit = async (e) => {
                e.preventDefault();
                const status = document.getElementById('status');
                status.innerText = "Transfiriendo datos...";

                const formData = new FormData(e.target);
                // Enviamos el rol manualmente para que PHP lo valide
                formData.append('role', userRole); 

                try {
                    const resp = await fetch('upload_logic.php', { method: 'POST', body: formData });
                    const result = await resp.json();
                    if(result.success) {
                        alert("¡Sistema Actualizado!");
                        location.reload();
                    } else {
                        status.innerText = "ERROR: " + result.error;
                    }
                } catch (err) {
                    status.innerText = "Error de comunicación con el servidor.";
                }
            };
        }
    </script>
</body>
</html>