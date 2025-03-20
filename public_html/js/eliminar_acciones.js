function eliminarAccion(element) {
    var ticker = element.closest('tr').getAttribute('data-ticker');
    var cliente_id = document.querySelector('meta[name="cliente_id"]').getAttribute('content');

    if (confirm("¿Estás seguro de que deseas eliminar esta acción?")) {
        $.ajax({
            url: '../funciones/eliminar_acciones.php',
            type: 'POST',
            data: {
                cliente_id: cliente_id,
                ticker: ticker
            },
            success: function(response) {
                var data = JSON.parse(response);
                if (data.status === 'success') {
                    alert("Acción eliminada exitosamente.");
                    location.reload(); // Recargar página para actualizar cambios
                } else {
                    alert("Error al eliminar la acción.");
                }
            },
            error: function() {
                alert("Error en la solicitud.");
            }
        });
    }
}