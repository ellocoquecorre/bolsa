function eliminarAcciones(ticker, cliente_id) {
    // Confirmar eliminación
    if (confirm('¿Estás seguro de que deseas eliminar esta acción?')) {
        // Crear objeto con los datos a enviar
        const data = {
            ticker: ticker,
            cliente_id: cliente_id
        };

        console.log("Datos enviados:", data); // Esto imprimirá los datos en la consola del navegador

        // Realizar la solicitud AJAX al archivo PHP
        fetch('../funciones/eliminar_acciones.php', {  // Ruta a tu archivo PHP
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(data)  // Enviar los datos como JSON
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Acción eliminada correctamente');
                location.reload(); // Recargar la página para actualizar la tabla
            } else {
                alert('Hubo un error al eliminar la acción: ' + data.message);
            }
        })
        .catch(error => {
            alert('Error al realizar la operación');
            console.error(error);
        });
    }
}
