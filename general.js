function comprarUna(idSesion) {
    console.log("Enviando ID:", idSesion);
    if (idSesion && idSesion !== 0) {
        window.location.href = `comprar.php?id=${idSesion}`;
    } else {
        alert("Error: No se ha detectado un ID de sesión válido en este botón.");
    }
}