<?php
// Iniciar una sesion de usuario para almacenar los datos.
session_start();

// Comprueba si se ha enviado un parámetro 'id' a través de la URL (por ejemplo: logic.php?id=5)
if (isset($_GET['id'])) {
    
// Convierte el valor recibido a un número entero (int)
    $idSesion = (int)$_GET['id'];

// Verifica que el ID sea válido (diferente de cero)
    if ($idSesion !== 0) {
        
// Guarda el ID de la sesión elegida en el carrito del usuario
        $_SESSION['carrito_sesion'] = $idSesion;
        
// Redirige automáticamente al usuario a la página del carrito
        header("Location: carrito.php");
        
// Finaliza la ejecución del script para asegurar la redirección
        exit();
        
    } else {
// Si el ID es 0 o no es un número válido, vuelve al inicio con un mensaje de error
        header("Location: index.php?error=id_invalido");
        exit();
    }
} else {
// Si alguien intenta entrar a esta página sin enviar un ID, lo devuelve al inicio
    header("Location: index.php?error=sin_id");
    exit();
}
?>
