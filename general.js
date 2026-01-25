function comprarUna(id) {
    const spanStock = document.getElementById('stock-' + id);
    const boton = document.getElementById('btn-' + id);
    let stockActual = parseInt(spanStock.innerText);

    if (stockActual > 0) {
        spanStock.innerText = stockActual - 1;
        boton.disabled = true;
        boton.innerText = "Seleccionada";
    }
}