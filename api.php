<?php
// 1. Configuración de errores para verlos en los Logs de Render
ini_set('display_errors', 1);
error_reporting(E_ALL);

$host = getenv('DB_HOST');
$user = getenv('DB_USER');
$pass = getenv('DB_PASSWORD');
$db   = getenv('DB_NAME');
$port = getenv('DB_PORT');

// 2. Conexión Segura
$conn = mysqli_init();
mysqli_ssl_set($conn, NULL, NULL, NULL, NULL, NULL);
if (!mysqli_real_connect($conn, $host, $user, $pass, $db, $port, NULL, MYSQLI_CLIENT_SSL)) {
    header('Content-Type: application/json');
    die(json_encode(["success" => false, "error" => "Error de conexión: " . mysqli_connect_error()]));
}

header('Content-Type: application/json');

// 3. LEER EL JSON (UNA SOLA VEZ)
$rawInput = file_get_contents('php://input');
$input = json_decode($rawInput, true);

$metodo = $_SERVER['REQUEST_METHOD'];
$accion = $input['accion'] ?? '';

if ($metodo === 'POST') {
    if ($accion === 'registrar') {
        $u = mysqli_real_escape_string($conn, $input['nombre'] ?? '');
        $p = mysqli_real_escape_string($conn, $input['clave'] ?? '');
        $r = mysqli_real_escape_string($conn, $input['rango'] ?? 'Visitante');

        if (empty($u) || empty($p)) {
            echo json_encode(["success" => false, "error" => "Datos incompletos"]);
            exit;
        }

        // Intento de inserción
        $sql = "INSERT INTO usuarios (nombre, clave, rango) VALUES ('$u', '$p', '$r')";
        
        if (mysqli_query($conn, $sql)) {
            echo json_encode(["success" => true]);
        } else {
            // Esto te dirá en el log de Render si falta una columna o el ID falla
            echo json_encode(["success" => false, "error" => "Error SQL: " . mysqli_error($conn)]);
        }
    } 
    elseif ($accion === 'login') {
        $u = mysqli_real_escape_string($conn, $input['nombre'] ?? '');
        $p = mysqli_real_escape_string($conn, $input['clave'] ?? '');
        
        $res = mysqli_query($conn, "SELECT nombre, rango FROM usuarios WHERE nombre = '$u' AND clave = '$p'");
        $user_data = mysqli_fetch_assoc($res);
        
        if ($user_data) {
            echo json_encode(["success" => true, "user" => $user_data]);
        } else {
            echo json_encode(["success" => false, "error" => "Usuario no encontrado"]);
        }
    }
    exit;
}

// 4. GET para lista de usuarios
if ($metodo === 'GET') {
    $res = mysqli_query($conn, "SELECT nombre, rango FROM usuarios");
    echo json_encode(mysqli_fetch_all($res, MYSQLI_ASSOC));
    exit;
}

mysqli_close($conn);
?>