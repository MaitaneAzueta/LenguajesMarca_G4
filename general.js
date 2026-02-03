// 1. Mensaje de error si la contraseña está mal (se ejecuta al cargar)
if (window.location.search.includes("errorea=1")) {
    alert("Usuario o contraseña incorrectos");
}

// 2. Función para los enlaces que requieren login
function avisarLogin() {
    alert("Necesitas iniciar sesión para acceder a esta sección.");
}

// 3. Función para ir a la página de compra
function comprarUna(id) {
    if (id > 0) {
        window.location.href = "comprar.php?id=" + id;
    } else {
        alert("ID de sesión no válido.");
    }
}

// 4. Función de confirmación para el formulario
function confirmar() {
    return confirm("¿Está seguro de que quiere realizar la compra?");
}

function mensaje_cerrarSesion() {
    return confirm("¿Está seguro de que quiere cerrar sesión?");
}

function comprarEntrada() {
    return confirm("¿Está seguro de que quiere comprar esta entrada?");
}