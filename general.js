/* ============================================================
   LÓGICA DE CONTROL - HISPANIA TRAVEL S.A.
   Gestión de Staff: Arima, Igor, Manu, Chofer
   ============================================================ */

document.addEventListener("DOMContentLoaded", () => {
    actualizarInterfaz();
});

/**
 * 1. VALIDACIÓN DE ACCESO
 * Base de datos estática de Hispania Travel
 */
function validarAcceso() {
    const u = document.getElementById("usuario_input").value;
    const p = document.getElementById("clave_input").value;

    // --- ACCESO ADMINISTRACIÓN ---
    if (u === "admin" && p === "root") {
        localStorage.setItem("ht_user", "Arima"); // Solo el nombre como pediste
        localStorage.setItem("ht_role", "ADMIN");
        alert("Acceso concedido. Bienvenida, Arima.");
        window.location.href = "index.php";
    } 
    
    // --- NUEVO USUARIO: IGOR ---
    else if (u === "igor" && p === "ht2024") {
        localStorage.setItem("ht_user", "Igor");
        localStorage.setItem("ht_role", "CLIENTE");
        alert("Conexión establecida. Buen servicio, Igor.");
        window.location.href = "index.php";
    }

    // --- NUEVO USUARIO: MANU ---
    else if (u === "manu" && p === "ruta88") {
        localStorage.setItem("ht_user", "Manu");
        localStorage.setItem("ht_role", "CLIENTE");
        alert("Conexión establecida. Buen servicio, Manu.");
        window.location.href = "index.php";
    }

    // --- CHOFER GENÉRICO ---
    else if (u === "chofer" && p === "1234") {
        localStorage.setItem("ht_user", "Chofer Staff");
        localStorage.setItem("ht_role", "CLIENTE");
        alert("Buen viaje con Hispania Travel.");
        window.location.href = "index.php";
    } 
    
    else {
        alert("ERROR: Credenciales de Hispania Travel incorrectas.");
    }
}

/**
 * 2. PROTECCIÓN DE SECCIONES
 */
function verificarAcceso(e, seccion) {
    if (!localStorage.getItem("ht_user")) {
        e.preventDefault();
        alert("ACCESO DENEGADO. Inicie sesión para consultar: " + seccion);
        window.location.href = "login.php";
    }
}

/**
 * 3. INTERFAZ DINÁMICA
 */
function actualizarInterfaz() {
    const user = localStorage.getItem("ht_user");
    const role = localStorage.getItem("ht_role");
    
    const saludo = document.getElementById("saludo-trucker");
    const btnSesion = document.getElementById("btn-sesion");
    const vistaAdmin = document.getElementById("vista-admin");
    const vistaCliente = document.getElementById("vista-cliente");
    const vistaPublica = document.getElementById("vista-publica");

    if (user) {
        // Mostrar solo el nombre del usuario logueado
        if (saludo) saludo.innerText = "USUARIO: " + user;
        
        if (btnSesion) {
            btnSesion.innerText = "DESCONECTAR";
            btnSesion.onclick = cerrarSesion;
            btnSesion.href = "#";
        }

        // Gestión de vistas por rol
        if (role === "ADMIN") {
            if (vistaAdmin) vistaAdmin.style.display = "block";
            if (vistaCliente) vistaCliente.style.display = "none";
        } else {
            if (vistaAdmin) vistaAdmin.style.display = "none";
            if (vistaCliente) vistaCliente.style.display = "block";
        }
    }
}

function cerrarSesion() {
    if (confirm("¿Cerrar sesión en la terminal de Hispania Travel?")) {
        localStorage.clear();
        window.location.href = "index.php";
    }
}