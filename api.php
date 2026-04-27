<?php
// Configuración de conexión con SSL para TiDB
$host = getenv('DB_HOST');
$user = getenv('DB_USER');
$pass = getenv('DB_PASSWORD');
$db   = getenv('DB_NAME');
$port = getenv('DB_PORT');

$conn = mysqli_init();
mysqli_ssl_set($conn, NULL, NULL, NULL, NULL, NULL);
mysqli_real_connect($conn, $host, $user, $pass, $db, $port, NULL, MYSQLI_CLIENT_SSL);

header('Content-Type: application/json');
$metodo = $_SERVER['REQUEST_METHOD'];
$input = json_decode(file_get_contents('php://input'), true);

// 1. REGISTRAR USUARIO
if ($metodo == 'POST' && $accion == 'registrar') {
    $u = mysqli_real_escape_string($conn, $input['nombre']);
    $p = mysqli_real_escape_string($conn, $input['clave']);
    $r = $input['rango'];
    $sql = "INSERT INTO usuarios (nombre, clave, rango) VALUES ('$u', '$p', '$r')";
    echo json_encode(["success" => mysqli_query($conn, $sql)]);
}

// 2. VALIDAR ACCESO (LOGIN)
else if ($metodo == 'POST' && $accion == 'login') {
    $u = mysqli_real_escape_string($conn, $input['nombre']);
    $p = mysqli_real_escape_string($conn, $input['clave']);
    $res = mysqli_query($conn, "SELECT nombre, rango FROM usuarios WHERE nombre = '$u' AND clave = '$p'");
    if ($user = mysqli_fetch_assoc($res)) {
        echo json_encode(["success" => true, "user" => $user]);
    } else {
        echo json_encode(["success" => false]);
    }
}
// 3. OBTENER LISTA (GESTIÓN)
if ($metodo == 'GET') {
    $res = mysqli_query($conn, "SELECT nombre, rango FROM usuarios");
    echo json_encode(mysqli_fetch_all($res, MYSQLI_ASSOC));
}
?>

// 4. BORRAR USUARIO (Añadir a api.php)
if ($metodo == 'POST' && $accion == 'borrar') {
    $u = mysqli_real_escape_string($conn, $input['nombre']);
    $sql = "DELETE FROM usuarios WHERE nombre = '$u'";
    echo json_encode(["success" => mysqli_query($conn, $sql)]);
}