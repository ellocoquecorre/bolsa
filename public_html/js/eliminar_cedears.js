function eliminarCedear(element) {
    var ticker = element.closest('tr').getAttribute('data-ticker');
    var cliente_id = document.querySelector('meta[name="cliente_id"]').getAttribute('content');

    if (confirm("¿Estás seguro que querés eliminar este Cedear?")) {
        $.ajax({
            url: '../funciones/eliminar_cedears.php',
            type: 'POST',
            data: {
                cliente_id: cliente_id,
                ticker: ticker
            },
            success: function(response) {
                var data = JSON.parse(response);
                if (data.status === 'success') {
                    alert("Cedear eliminado exitosamente.");
                    location.reload(); // Recargar página para actualizar cambios
                } else {
                    alert("Error al eliminar el Cedear.");
                }
            },
            error: function() {
                alert("Error en la solicitud.");
            }
        });
    }
}