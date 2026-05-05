<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

$host = getenv('DB_HOST');
$user = getenv('DB_USER');
$pass = getenv('DB_PASSWORD');
$db   = getenv('DB_NAME');
$port = getenv('DB_PORT') ?: 4000;

$conn = mysqli_init();
mysqli_ssl_set($conn, NULL, NULL, NULL, NULL, NULL);
if (!mysqli_real_connect($conn, $host, $user, $pass, $db, $port, NULL, MYSQLI_CLIENT_SSL)) {
    header('Content-Type: application/json');
    die(json_encode(["success" => false, "error" => "Conexión fallida"]));
}

header('Content-Type: application/json');

$rawInput = file_get_contents('php://input');
$input = json_decode($rawInput, true);
$metodo = $_SERVER['REQUEST_METHOD'];
$accion = $input['accion'] ?? '';

// --- OPERACIONES POST ---
if ($metodo === 'POST') {
    
    // 1. REGISTRAR
    if ($accion === 'registrar') {
        $u = mysqli_real_escape_string($conn, $input['nombre'] ?? '');
        $p = mysqli_real_escape_string($conn, $input['clave'] ?? '');
        $r = mysqli_real_escape_string($conn, $input['rango'] ?? 'Visitante');
        $sql = "INSERT INTO usuarios (nombre, clave, rango) VALUES ('$u', '$p', '$r')";
        echo json_encode(["success" => mysqli_query($conn, $sql)]);
    } 
    
    // 2. LOGIN
    elseif ($accion === 'login') {
        $u = mysqli_real_escape_string($conn, $input['nombre'] ?? '');
        $p = mysqli_real_escape_string($conn, $input['clave'] ?? '');
        $res = mysqli_query($conn, "SELECT nombre, rango FROM usuarios WHERE nombre = '$u' AND clave = '$p'");
        $user_data = mysqli_fetch_assoc($res);
        echo json_encode($user_data ? ["success" => true, "user" => $user_data] : ["success" => false]);
    }

    // 3. ACTUALIZAR RANGO (Nuevo)
    elseif ($accion === 'actualizar_rango') {
        $u = mysqli_real_escape_string($conn, $input['nombre'] ?? '');
        $nuevoRango = mysqli_real_escape_string($conn, $input['rango'] ?? '');
        
        // Solo permitimos estos tres valores exactos
        if (in_array($nuevoRango, ['Visitante', 'Conductor', 'Gestor'])) {
            $sql = "UPDATE usuarios SET rango = '$nuevoRango' WHERE nombre = '$u'";
            echo json_encode(["success" => mysqli_query($conn, $sql)]);
        } else {
            echo json_encode(["success" => false, "error" => "Rango inválido"]);
        }
    }

    // 4. BORRAR
    elseif ($accion === 'borrar') {
        $u = mysqli_real_escape_string($conn, $input['nombre'] ?? '');
        $sql = "DELETE FROM usuarios WHERE nombre = '$u'";
        echo json_encode(["success" => mysqli_query($conn, $sql)]);
    }
    exit;
}

// --- OPERACIONES GET ---
if ($metodo === 'GET') {
    $res = mysqli_query($conn, "SELECT nombre, rango FROM usuarios");
    echo json_encode(mysqli_fetch_all($res, MYSQLI_ASSOC));
    exit;
}

mysqli_close($conn);
?>