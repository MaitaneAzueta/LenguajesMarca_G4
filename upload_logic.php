<?php
session_start();

// 1. SEGURIDAD: Solo el rango 'Gestor' puede ejecutar este código
// Ajusta 'Gestor' al nombre exacto que uses en tu tabla 'usuarios'
if (!isset($_SESSION['rango']) || $_SESSION['rango'] !== 'Gestor') {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'error' => 'Acceso denegado: No tienes rango de Gestor']);
    exit();
}

// 2. CONFIGURACIÓN DE CLOUDINARY
// Consigue estos datos en tu Dashboard de Cloudinary
$cloud_name = "darlbycqo"; 
$upload_preset = "eventos_preset"; // Debes crearlo en Settings -> Upload -> Unsigned uploading

// 3. CONFIGURACIÓN DE TiDB (MySQL)
$host = 'gateway01.eu-central-1.prod.aws.tidbcloud.com';
$user = '2tvbKFgEYyWm58d.root';
$pass = 'TlDYiAeavwP9WiOO';
$db   = 'test';
$port = 4000;

// Conexión con SSL (Obligatorio para TiDB Cloud en Render)
$conn = mysqli_init();
$conn->ssl_set(NULL, NULL, "/etc/ssl/certs/ca-certificates.crt", NULL, NULL);
if (!$conn->real_connect($host, $user, $pass, $db, $port)) {
    die(json_encode(['success' => false, 'error' => 'Error de conexión a DB']));
}

// 4. PROCESO DE SUBIDA
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $texto = $_POST['texto'] ?? '';
    $archivo = $_FILES['foto']['tmp_name'];

    if (empty($archivo)) {
        echo json_encode(['success' => false, 'error' => 'No se seleccionó ninguna imagen']);
        exit();
    }

    // A. Subir la imagen a Cloudinary mediante CURL
    $url_cloudinary = "https://api.cloudinary.com/v1_1/$cloud_name/image/upload";
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url_cloudinary);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, [
        'file' => new CURLFile($archivo),
        'upload_preset' => $upload_preset
    ]);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    
    $result_json = curl_exec($ch);
    $response = json_decode($result_json, true);
    curl_close($ch);

    // B. Si la subida fue exitosa, guardar en TiDB
    if (isset($response['secure_url'])) {
        $imagen_url = $response['secure_url'];

        $sql = "INSERT INTO publicaciones (texto, imagen_url) VALUES (?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $texto, $imagen_url);

        if ($stmt->execute()) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'error' => 'Error al guardar en base de datos']);
        }
    } else {
        echo json_encode(['success' => false, 'error' => 'Error al subir a la nube']);
    }
}
?>