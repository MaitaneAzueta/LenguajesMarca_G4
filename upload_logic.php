<?php
header('Content-Type: application/json');

// 1. Recibimos el rol enviado desde el JavaScript
$rango_recibido = $_POST['role'] ?? '';

if ($rango_recibido !== 'Gestor') {
    echo json_encode(['success' => false, 'error' => 'Permiso denegado: Se requiere rango Gestor']);
    exit();
}

// 2. CONFIGURACIÓN CLOUDINARY
$cloud_name = "darlbycqo"; 
$upload_preset = "eventos_preset"; // El que creaste en Cloudinary

// 3. CONFIGURACIÓN TiDB
$host = 'gateway01.eu-central-1.prod.aws.tidbcloud.com';
$user = '2tvbKFgEYyWm58d.root';
$pass = 'TlDYiAeavwP9WiOO';
$db   = 'test';
$port = 4000;

$conn = mysqli_init();
$conn->ssl_set(NULL, NULL, "/etc/ssl/certs/ca-certificates.crt", NULL, NULL);
$conn->real_connect($host, $user, $pass, $db, $port);

// 4. PROCESO
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $texto = $_POST['texto'];
    $archivo = $_FILES['foto']['tmp_name'];

    // Subida a Cloudinary
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "https://api.cloudinary.com/v1_1/$cloud_name/image/upload");
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, [
        'file' => new CURLFile($archivo),
        'upload_preset' => $upload_preset
    ]);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = json_decode(curl_exec($ch), true);
    curl_close($ch);

    if (isset($response['secure_url'])) {
        $url = $response['secure_url'];
        
        $stmt = $conn->prepare("INSERT INTO publicaciones (texto, imagen_url) VALUES (?, ?)");
        $stmt->bind_param("ss", $texto, $url);
        
        if ($stmt->execute()) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'error' => 'Error al escribir en TiDB']);
        }
    } else {
        echo json_encode(['success' => false, 'error' => 'Error al subir imagen a la nube']);
    }
}
?>