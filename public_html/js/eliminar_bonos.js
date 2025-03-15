function eliminarBono(button) {
    const clienteId = new URLSearchParams(window.location.search).get('cliente_id');
    const row = button.closest('tr');
    const ticker = row.querySelector('td:first-child').textContent.trim();

    // Preguntar al usuario si realmente quiere eliminar el bono
    const confirmacion = confirm(`¿Estás seguro de que querés eliminar ${ticker}?`);
    
    if (!confirmacion) {
        return; // Si el usuario cancela, no hacer nada
    }

    if (clienteId && ticker) {
        fetch('../funciones/eliminar_bonos.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                cliente_id: clienteId,
                ticker: ticker
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Bono eliminado con éxito');
                localStorage.setItem('scrollTo', '#bonos'); // Guardar en localStorage
                window.location.reload(true); // Recarga la página completamente
            } else {
                alert('Error al eliminar el bono');
            }
        });
    } else {
        alert('Error al obtener cliente_id o ticker');
    }
}

// Verificar si hay un elemento al que desplazarse después de la recarga
window.addEventListener('load', () => {
    const scrollTo = localStorage.getItem('scrollTo');
    if (scrollTo) {
        window.location.hash = scrollTo; // Desplazarse al elemento
        localStorage.removeItem('scrollTo'); // Limpiar localStorage
    }
});