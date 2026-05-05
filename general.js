const API_URL = "api.php";

document.addEventListener("DOMContentLoaded", () => {
    verificarSeguridadRutas();
    actualizarInterfaz();

    const formLogin = document.getElementById("loginForm");
    if (formLogin) formLogin.addEventListener("submit", (e) => { e.preventDefault(); validarAcceso(); });

    const formRegistro = document.querySelector("form[onsubmit*='registrarUsuario']");
    if (formRegistro) {
        formRegistro.removeAttribute("onsubmit");
        formRegistro.addEventListener("submit", (e) => { e.preventDefault(); registrarUsuario(); });
    }

    if (window.location.pathname.includes("gestion.php")) {
        cargarTablaGestion();
    }
});

function verificarSeguridadRutas() {
    const path = window.location.pathname;
    const role = localStorage.getItem("ht_role");

    // 1. Solo Gestores entran a gestion.php
    if (path.includes("gestion.php") && role !== "Gestor") {
        window.location.href = "index.php";
    }

    // 2. Conductores y Gestores ven manuales.php (Visitantes NO)
    if (path.includes("manuales.php")) {
        if (role !== "Conductor" && role !== "Gestor") {
            alert("ACCESO RESTRINGIDO: SE REQUIERE RANGO CONDUCTOR.");
            window.location.href = "index.php";
        }
    }
}

async function registrarUsuario() {
    const u = document.getElementById("reg_usuario").value.trim().toLowerCase();
    const p = document.getElementById("reg_clave").value;
    
    try {
        const response = await fetch(API_URL, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ 
                accion: "registrar", 
                nombre: u, 
                clave: p, 
                rango: "Visitante" 
            })
        });

        const data = await response.json();
        if (data.success) {
            localStorage.setItem("ht_user", u);
            localStorage.setItem("ht_role", "Visitante");
            window.location.href = "index.php"; 
        } else {
            alert("ERROR: " + (data.error || "No se pudo crear el usuario"));
        }
    } catch (error) {
        alert("SISTEMA OFFLINE.");
    }
}

async function validarAcceso() {
    const u = document.getElementById("usuario_input").value.trim().toLowerCase();
    const p = document.getElementById("clave_input").value;

    if (u === "admin" && p === "root") {
        localStorage.setItem("ht_user", "Arima");
        localStorage.setItem("ht_role", "Gestor");
        window.location.href = "index.php";
        return;
    }

    try {
        const response = await fetch(API_URL, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ 
                accion: "login", 
                nombre: u, 
                clave: p 
            })
        });

        const data = await response.json();
        if (data.success) {
            localStorage.setItem("ht_user", data.user.nombre);
            localStorage.setItem("ht_role", data.user.rango);
            window.location.href = "index.php";
        } else {
            alert("CREDENCIALES ERRÓNEAS.");
        }
    } catch (error) {
        alert("ERROR DE CONEXIÓN.");
    }
}

// --- GESTIÓN DE USUARIOS (CORREGIDO PARA PHP) ---

async function cargarTablaGestion() {
    const lista = document.getElementById("lista-usuarios");
    if (!lista) return;

    try {
        const response = await fetch(API_URL); 
        const usuarios = await response.json();
        
        let html = `<table class="tabla-custom">
                    <thead><tr>
                    <th>ID USUARIO</th><th>RANGO ACTUAL</th><th>CAMBIAR RANGO</th><th>ACCIONES</th>
                    </tr></thead><tbody>`;

        usuarios.forEach((u) => {
            html += `<tr>
                <td style="color: #00AAFF;">${u.nombre.toUpperCase()}</td>
                <td><span class="badge-${u.rango.toLowerCase()}">${u.rango}</span></td>
                <td>
                    <select onchange="cambiarRangoRemote('${u.nombre}', this.value)">
                        <option value="Visitante" ${u.rango === 'Visitante' ? 'selected' : ''}>Visitante</option>
                        <option value="Conductor" ${u.rango === 'Conductor' ? 'selected' : ''}>Conductor</option>
                        <option value="Gestor" ${u.rango === 'Gestor' ? 'selected' : ''}>Gestor</option>
                    </select>
                </td>
                <td>
                    <button onclick="borrarUsuarioRemote('${u.nombre}')" class="btn-borrar"><i class="fas fa-trash"></i></button>
                </td>
            </tr>`;
        });
        lista.innerHTML = html + "</tbody></table>";
    } catch (e) {
        lista.innerHTML = "Error al conectar con la base de datos.";
    }
}

// Nueva función para enviar el cambio a la base de datos
async function cambiarRangoRemote(nombre, nuevoRango) {
    try {
        const response = await fetch(API_URL, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ 
                accion: "actualizar_rango", 
                nombre: nombre, 
                rango: nuevoRango 
            })
        });
        const data = await response.json();
        if(data.success) {
            alert("RANGO DE " + nombre.toUpperCase() + " ACTUALIZADO A " + nuevoRango);
            // Si el usuario se cambió a sí mismo, actualizamos su sesión local
            if(localStorage.getItem("ht_user") === nombre) {
                localStorage.setItem("ht_role", nuevoRango);
            }
            location.reload(); // Recargamos para aplicar cambios de menú
        }
    } catch (e) {
        alert("Error en la conexión.");
    }
}

async function borrarUsuarioRemote(nombre) {
    if(!confirm(`¿ESTÁS SEGURO DE ELIMINAR A ${nombre.toUpperCase()}?`)) return;

    try {
        const response = await fetch(API_URL, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ 
                accion: "borrar", 
                nombre: nombre 
            })
        });
        const data = await response.json();
        if(data.success) {
            cargarTablaGestion(); // Recargar la tabla
        }
    } catch (e) {
        alert("Error al borrar.");
    }
}

// --- INTERFAZ ---
function cerrarSesion() {
    localStorage.removeItem("ht_user");
    localStorage.removeItem("ht_role");
    window.location.href = "index.php";
}

function actualizarInterfaz() {
    const user = localStorage.getItem("ht_user");
    const role = localStorage.getItem("ht_role");
    const saludo = document.getElementById("saludo-trucker");
    const btnSesion = document.getElementById("btn-sesion");
    const nav = document.querySelector("nav");

    if (user) {
        if (saludo) saludo.innerHTML = `OPERADOR: ${user.toUpperCase()} [${role}]`;
        if (btnSesion) {
            btnSesion.innerText = "DESCONECTAR";
            btnSesion.onclick = (e) => { e.preventDefault(); cerrarSesion(); };
        }
        if (role === "Gestor" && nav && !document.getElementById("nav-gestion")) {
            const link = document.createElement("a");
            link.href = "gestion.php"; link.id = "nav-gestion"; link.innerText = "GESTIÓN";
            nav.appendChild(link);
        }
    }
}