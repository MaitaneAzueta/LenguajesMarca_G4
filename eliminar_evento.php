<?php
header('Content-Type: application/json');

// 1. Seguridad: Verificar el rol enviado por JS
$rango = $_POST['role'] ?? '';
if ($rango !== 'Gestor') {
    echo json_encode(['success' => false, 'error' => 'No tienes permiso']);
    exit();
}

$id = $_POST['id'] ?? '';

// 2. Configuración TiDB
$host = 'gateway01.eu-central-1.prod.aws.tidbcloud.com';
$user = '2tvbKFgEYyWm58d.root';
$pass = 'TlDYiAeavwP9WiOO';
$db   = 'test';
$port = 4000;

$conn = mysqli_init();
$conn->ssl_set(NULL, NULL, "/etc/ssl/certs/ca-certificates.crt", NULL, NULL);
$conn->real_connect($host, $user, $pass, $db, $port);

if ($id) {
    $stmt = $conn->prepare("DELETE FROM publicaciones WHERE id = ?");
    $stmt->bind_param("i", $id);
    
    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => 'No se pudo eliminar de la base de datos']);
    }
}
?>