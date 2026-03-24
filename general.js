document.addEventListener("DOMContentLoaded", () => {
    // 1. Ejecutar Seguridad de Rutas
    verificarSeguridadRutas();
    
    // 2. Actualizar Interfaz (Saludos, Botones, Nav)
    actualizarInterfaz();

    // 3. Asignar Eventos a Formularios (si existen en la página)
    const formLogin = document.getElementById("loginForm");
    if (formLogin) formLogin.addEventListener("submit", (e) => { e.preventDefault(); validarAcceso(); });

    const formRegistro = document.querySelector("form[onsubmit*='registrarUsuario']");
    if (formRegistro) {
        formRegistro.removeAttribute("onsubmit");
        formRegistro.addEventListener("submit", (e) => { e.preventDefault(); registrarUsuario(); });
    }

    // 4. Si es la página de gestión, cargar la tabla
    if (window.location.pathname.includes("gestion.php")) {
        cargarTablaGestion();
    }

    // 5. Asignar evento al botón de Manuales en el nav
    const linkManuales = document.getElementById("nav-manuales");
    if (linkManuales) {
        linkManuales.addEventListener("click", (e) => {
            const role = localStorage.getItem("ht_role");
            if (role !== 'Conductor' && role !== 'Gestor') {
                e.preventDefault();
                alert("ACCESO DENEGADO: SE REQUIERE RANGO [CONDUCTOR]");
            }
        });
    }
});

// --- LÓGICA DE SEGURIDAD CENTRALIZADA ---

function verificarSeguridadRutas() {
    const path = window.location.pathname;
    const role = localStorage.getItem("ht_role");

    // Bloqueo total de Gestión
    if (path.includes("gestion.php") && role !== "Gestor") {
        window.location.href = "index.php";
    }

    // Bloqueo total de Manuales
    if (path.includes("manuales.php") && (role !== "Conductor" && role !== "Gestor")) {
        window.location.href = "index.php";
    }
}

// --- FUNCIONES DE BASE DE DATOS Y SESIÓN ---

function registrarUsuario() {
    const u = document.getElementById("reg_usuario").value.trim().toLowerCase();
    const p = document.getElementById("reg_clave").value;
    
    let usuarios = JSON.parse(localStorage.getItem("ht_db_usuarios")) || [];
    if (usuarios.find(user => user.nombre === u)) return alert("El usuario ya existe.");

    usuarios.push({ nombre: u, clave: p, rango: "Visitante" });
    localStorage.setItem("ht_db_usuarios", JSON.stringify(usuarios));
    
    localStorage.setItem("ht_user", u);
    localStorage.setItem("ht_role", "Visitante");
    window.location.href = "index.php"; 
}

function validarAcceso() {
    const u = document.getElementById("usuario_input").value.trim().toLowerCase();
    const p = document.getElementById("clave_input").value;

    if (u === "admin" && p === "root") {
        localStorage.setItem("ht_user", "Arima");
        localStorage.setItem("ht_role", "Gestor");
        window.location.href = "index.php";
        return;
    }

    let usuarios = JSON.parse(localStorage.getItem("ht_db_usuarios")) || [];
    const encontrado = usuarios.find(user => user.nombre === u && user.clave === p);

    if (encontrado) {
        localStorage.setItem("ht_user", encontrado.nombre);
        localStorage.setItem("ht_role", encontrado.rango);
        window.location.href = "index.php";
    } else {
        alert("CREDENCIALES ERRÓNEAS.");
    }
}

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

        // Crear botón de Gestión si es Gestor y no existe
        if (role === "Gestor" && nav && !document.getElementById("nav-gestion")) {
            const link = document.createElement("a");
            link.href = "gestion.php";
            link.id = "nav-gestion";
            link.innerText = "GESTIÓN";
            nav.appendChild(link);
        }
    }

    // --- LÓGICA DE BOTÓN ACTIVO AUTOMÁTICO ---
    const links = document.querySelectorAll("nav a");
    const rutaActual = window.location.pathname.split("/").pop() || "index.php";

    links.forEach(link => {
        // Si el href del enlace coincide con el nombre del archivo actual
        if (link.getAttribute("href") === rutaActual) {
            link.classList.add("active");
        } else {
            link.classList.remove("active");
        }
    });
}

function cargarTablaGestion() {
    const lista = document.getElementById("lista-usuarios");
    if (!lista) return;

    const usuarios = JSON.parse(localStorage.getItem("ht_db_usuarios")) || [];
    
    if (usuarios.length === 0) {
        lista.innerHTML = "<p class='texto-rojo' style='text-align:center; padding:20px;'>SISTEMA VACÍO: NO HAY REGISTROS EN LA BASE DE DATOS.</p>";
        return;
    }

    let html = `
        <table style="width:100%; border-collapse: collapse; color: white; font-size: 0.85rem; border: 1px solid #333;">
            <thead>
                <tr style="background-color: rgba(0, 170, 255, 0.1); border-bottom: 2px solid #FF0000;">
                    <th style="padding: 12px; border: 1px solid #333; text-align: left;">ID USUARIO</th>
                    <th style="padding: 12px; border: 1px solid #333; text-align: left;">RANGO ACTUAL</th>
                    <th style="padding: 12px; border: 1px solid #333; text-align: center;">CAMBIAR RANGO</th>
                    <th style="padding: 12px; border: 1px solid #333; text-align: center;">ELIMINAR</th>
                </tr>
            </thead>
            <tbody>`;

    usuarios.forEach((u, i) => {
        // No permitimos que el admin "Arima" se borre a sí mismo por error si está en la lista
        const esAdminGlobal = u.nombre.toLowerCase() === "arima";

        html += `
            <tr style="border-bottom: 1px solid #333; background-color: ${i % 2 === 0 ? 'transparent' : 'rgba(255,255,255,0.02)'};">
                <td style="padding: 10px; border: 1px solid #333; font-weight: bold; color: #00AAFF;">
                    <i class="fas fa-user-circle"></i> ${u.nombre.toUpperCase()}
                </td>
                <td style="padding: 10px; border: 1px solid #333; text-align: center;">
                    <span style="color: ${u.rango === 'Gestor' ? '#FF0000' : '#FFF'}; font-size: 0.75rem; border: 1px solid rgba(255,255,255,0.2); padding: 2px 6px;">
                        ${u.rango.toUpperCase()}
                    </span>
                </td>
                <td style="padding: 10px; border: 1px solid #333;">
                    <select onchange="cambiarRango(${i}, this.value)" 
                            style="background: #000; color: #00AAFF; border: 1px solid #00AAFF; padding: 4px; cursor: pointer; width: 100%;">
                        <option value="">Seleccionar...</option>
                        <option value="Visitante">Visitante</option>
                        <option value="Conductor">Conductor</option>
                        <option value="Gestor">Gestor</option>
                    </select>
                </td>
                <td style="padding: 10px; border: 1px solid #333; text-align: center;">
                    ${esAdminGlobal ? 
                        '<i class="fas fa-lock" style="color:#555"></i>' : 
                        `<button onclick="borrarUsuario(${i})" style="background:none; border:none; color:#FF0000; cursor:pointer; font-size:1.1rem;">
                            <i class="fas fa-trash-alt"></i>
                        </button>`
                    }
                </td>
            </tr>`;
    });

    html += `</tbody></table>`;
    lista.innerHTML = html;
}

function borrarUsuario(index) {
    let usuarios = JSON.parse(localStorage.getItem("ht_db_usuarios")) || [];
    const nombreUsuario = usuarios[index].nombre.toUpperCase();

    // Confirmación de seguridad
    if (confirm(`¿ESTÁ SEGURO DE ELIMINAR AL USUARIO [${nombreUsuario}] DEL SISTEMA?`)) {
        usuarios.splice(index, 1); // Elimina el elemento del array
        localStorage.setItem("ht_db_usuarios", JSON.stringify(usuarios));
        location.reload(); // Refresca para actualizar la tabla
    }
}