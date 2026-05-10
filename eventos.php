<?php
/**
 * HISPANIA TRAVEL S.A. - TERMINAL DE EVENTOS
 * Conexión con TiDB Cloud y Visualización Dinámica
 */

// 1. CONFIGURACIÓN DE LA BASE DE DATOS (TiDB)
// Sustituye con tus credenciales reales de TiDB Cloud
$host = 'gateway01.eu-central-1.prod.aws.tidbcloud.com'; 
$user = '2tvbKFgEYyWm58d.root';
$pass = 'TlDYiAeavwP9WiOO';
$db   = 'test';
$port = 4000;

$conn = mysqli_init();
// Certificado SSL obligatorio para Render -> TiDB
$conn->ssl_set(NULL, NULL, "/etc/ssl/certs/ca-certificates.crt", NULL, NULL);

// Intentar conexión
if (!$conn->real_connect($host, $user, $pass, $db, $port)) {
    $db_error = "Error de conexión a la Terminal de Datos.";
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hispania Travel - Eventos</title>
    <style>
        :root {
            --primary: #ff0000;
            --bg: #0b0b0b;
            --card-bg: #141414;
            --text: #ffffff;
            --border: #333333;
        }

        body {
            background-color: var(--bg);
            color: var(--text);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 20px;
            line-height: 1.6;
        }

        .container { max-width: 900px; margin: 0 auto; }
        
        h1 { border-left: 5px solid var(--primary); padding-left: 15px; text-transform: uppercase; letter-spacing: 2px; }

        /* Panel de subida para Gestores */
        #panel-gestor {
            display: none; /* Se activa por JS */
            background: var(--card-bg);
            border: 1px solid var(--primary);
            padding: 25px;
            margin-bottom: 40px;
            border-radius: 4px;
        }

        #panel-gestor h3 { color: var(--primary); margin-top: 0; }

        textarea {
            width: 100%;
            height: 100px;
            background: #1a1a1a;
            color: white;
            border: 1px solid var(--border);
            padding: 12px;
            box-sizing: border-box;
            resize: vertical;
            margin-bottom: 15px;
        }

        input[type="file"] { margin-bottom: 15px; display: block; color: #ccc; }

        .btn-post {
            background: var(--primary);
            color: white;
            border: none;
            padding: 12px 25px;
            cursor: pointer;
            font-weight: bold;
            transition: 0.3s;
        }

        .btn-post:hover { background: #b30000; }

        /* Tarjetas de Eventos */
        .evento-card {
            background: var(--card-bg);
            border: 1px solid var(--border);
            margin-bottom: 30px;
            border-radius: 4px;
            overflow: hidden;
            transition: transform 0.2s;
        }

        .evento-content { padding: 20px; }
        
        .evento-card img {
            width: 100%;
            height: auto;
            display: block;
            border-top: 1px solid var(--border);
            max-height: 500px;
            object-fit: contain;
            background: #000;
        }

        .fecha { color: #666; font-size: 0.85rem; }

        .btn-delete {
            background: #222;
            color: #ff4d4d;
            border: 1px solid #444;
            padding: 5px 10px;
            cursor: pointer;
            margin-top: 15px;
            font-size: 0.75rem;
            display: none; /* Se activa por JS */
        }

        .btn-delete:hover { background: #333; border-color: #ff0000; }
        
        #status { font-weight: bold; margin-top: 10px; }
    </style>
</head>
<body>

<div class="container">
    <h1>Terminal de Eventos</h1>

    <?php if (isset($db_error)): ?>
        <p style="color: red;"><?php echo $db_error; ?></p>
    <?php endif; ?>

    <section id="panel-gestor">
        <h3>NUEVA ENTRADA DE SISTEMA</h3>
        <form id="uploadForm">
            <textarea name="texto" placeholder="Escribe el reporte, noticia o evento corporativo..." required></textarea>
            <label>Adjuntar imagen del evento:</label>
            <input type="file" name="foto" accept="image/*" required>
            <button type="submit" class="btn-post">PUBLICAR EN LA RED</button>
        </form>
        <p id="status"></p>
    </section>

    <section id="lista-eventos">
        <?php
        if (isset($conn) && !isset($db_error)) {
            $query = "SELECT * FROM publicaciones ORDER BY fecha_creacion DESC";
            $res = $conn->query($query);

            if ($res && $res->num_rows > 0) {
                while ($row = $res->fetch_assoc()) {
                    echo '<article class="evento-card" id="post-' . $row['id'] . '">';
                    echo '  <div class="evento-content">';
                    echo '    <span class="fecha">' . $row['fecha_creacion'] . '</span>';
                    echo '    <p>' . nl2br(htmlspecialchars($row['texto'])) . '</p>';
                    
                    // Botón eliminar (Solo visible para Gestor vía JS)
                    echo '    <button class="btn-delete" onclick="eliminarPost(' . $row['id'] . ')">ELIMINAR ENTRADA</button>';
                    echo '  </div>';
                    echo '  <img src="' . $row['imagen_url'] . '" alt="Imagen del evento">';
                    echo '</article>';
                }
            } else {
                echo '<p>No hay transmisiones activas en este momento.</p>';
            }
        }
        ?>
    </section>
</div>

<script>
    // 1. SINCRONIZACIÓN CON TU SISTEMA DE LOGIN (JavaScript localStorage)
    const role = localStorage.getItem("ht_role");
    const user = localStorage.getItem("ht_user");

    // 2. CONTROL DE ACCESO VISUAL
    if (role === "Gestor") {
        // Mostrar panel de subida
        document.getElementById('panel-gestor').style.display = 'block';
        // Mostrar todos los botones de eliminar
        document.querySelectorAll('.btn-delete').forEach(btn => btn.style.display = 'inline-block');
    }

    // Redirigir si no ha iniciado sesión (seguridad básica)
    if (!user) {
        alert("Acceso denegado. Por favor, inicie sesión.");
        window.location.href = "index.php";
    }

    // 3. LÓGICA DE SUBIDA (FETCH A upload_logic.php)
    const uploadForm = document.getElementById('uploadForm');
    if (uploadForm) {
        uploadForm.onsubmit = async (e) => {
            e.preventDefault();
            const status = document.getElementById('status');
            status.style.color = "yellow";
            status.innerText = "ENVIANDO DATOS A LA NUBE...";

            const formData = new FormData(uploadForm);
            formData.append('role', role); // Enviamos el rol para validación en servidor

            try {
                const resp = await fetch('upload_logic.php', { method: 'POST', body: formData });
                const result = await resp.json();

                if (result.success) {
                    status.style.color = "#00ff00";
                    status.innerText = "SISTEMA ACTUALIZADO CON ÉXITO.";
                    setTimeout(() => location.reload(), 1000);
                } else {
                    status.style.color = "red";
                    status.innerText = "ERROR: " + result.error;
                }
            } catch (err) {
                status.style.color = "red";
                status.innerText = "ERROR DE CONEXIÓN CON EL SERVIDOR.";
            }
        };
    }

    // 4. LÓGICA DE ELIMINACIÓN
    async function eliminarPost(id) {
        if (!confirm("¿Desea eliminar permanentemente esta entrada del sistema?")) return;

        const formData = new FormData();
        formData.append('id', id);
        formData.append('role', role);

        try {
            const resp = await fetch('eliminar_evento.php', { method: 'POST', body: formData });
            const result = await resp.json();

            if (result.success) {
                const element = document.getElementById('post-' + id);
                element.style.opacity = '0';
                setTimeout(() => element.remove(), 500);
            } else {
                alert("Error al eliminar: " + result.error);
            }
        } catch (err) {
            alert("Error de comunicación.");
        }
    }
</script>

</body>
</html>