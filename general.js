window.onload = function() {
    const enlacesLogin = document.querySelectorAll('.enlace-login');

    enlacesLogin.forEach(function(enlace) {
        enlace.addEventListener('click', function(event) {
            alert("Necesitas iniciar sesión para acceder a esta sección.");
            
        });
    });
};

function verificar() {
    if (window.location.search.includes("errorea=1")) {
        alert("Usuario o contraseña incorrectos");
        window.history.replaceState({}, '', window.location.pathname);
    }
}

function carrito() {
    alert("Producto añadido al carrito");
}

function comprarUna(idSesion) {
    console.log("Enviando ID:", idSesion);
    if (idSesion && idSesion !== 0) {
        window.location.href = `comprar.php?id=${idSesion}`;
    } else {
        alert("Error: No se ha detectado un ID de sesión válido.");
    }
}

document.addEventListener('DOMContentLoaded', () => {
    verificar();

    const formularioCompra = document.querySelector('form[action="finalizar_compra.php"]');
    
    if (formularioCompra) {
        formularioCompra.addEventListener('submit', function(event) {

            const boton = document.activeElement;

            if (boton && boton.value === 'confirmar') {
                const respuesta = confirm("¿Está seguro de que quiere realizar la compra?");
                if (!respuesta) {
                    event.preventDefault();
                }
            }

        });
    }
});
