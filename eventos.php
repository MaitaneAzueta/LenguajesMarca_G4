<?php
session_start();

// 1. Protección de Rango 1 (Si no tiene rango o es el nivel más bajo, fuera)
if (!isset($_SESSION['rango']) || $_SESSION['rango'] == 'Visitante') {
    header("Location: index.php");
    exit();
}

$mi_rango = $_SESSION['rango'];
$conexion = new mysqli("tu_host", "tu_user", "tu_pass", "test", 4000);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <title>Sección de Eventos</title>
    <link rel="stylesheet" href="estilos.css">
</head>
<body>
    <h1>Cartelera de Eventos</h1>

    <?php if ($mi_rango == 'Gestor'): ?>
        <section style="background: #f4f4f4; padding: 20px; border-radius: 8px;">
            <h3>Subir Nuevo Evento</h3>
            <form id="uploadForm">
                <textarea name="texto" placeholder="Descripción del evento..." required></textarea><br>
                <input type="file" name="foto" accept="image/*" required><br><br>
                <button type="submit">Publicar en la Web</button>
            </form>
            <p id="status"></p>
        </section>
        <hr>
    <?php endif; ?>

    <section id="lista-eventos">
        <?php
        $res = $conexion->query("SELECT * FROM publicaciones ORDER BY fecha_creacion DESC");
        while ($row = $res->fetch_assoc()): ?>
            <div class="evento-card">
                <p><?php echo htmlspecialchars($row['texto']); ?></p>
                <img src="<?php echo $row['imagen_url']; ?>" width="300">
                <small><?php echo $row['fecha_creacion']; ?></small>
            </div>
        <?php endwhile; ?>
    </section>

    <?php if ($mi_rango == 'Gestor'): ?>
    <script>
    document.getElementById('uploadForm').onsubmit = async (e) => {
        e.preventDefault();
        const status = document.getElementById('status');
        status.innerText = "Subiendo imagen...";

        const formData = new FormData(e.target);
        const resp = await fetch('upload_logic.php', { method: 'POST', body: formData });
        const result = await resp.json();

        if(result.success) {
            location.reload(); // Recargamos para ver el nuevo evento
        } else {
            status.innerText = "Error: " + result.error;
        }
    };
    </script>
    <?php endif; ?>
</body>
</html>