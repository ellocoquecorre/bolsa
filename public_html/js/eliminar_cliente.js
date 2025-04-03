document.addEventListener('DOMContentLoaded', function () {
    const botonesEliminar = document.querySelectorAll('.btn-custom.eliminar');

    botonesEliminar.forEach(function (boton) {
        boton.addEventListener('click', function () {
            const cliente_id = this.dataset.clienteId;

            if (confirm('Querés eliminar este usuario?')) {
                fetch('../funciones/eliminar_cliente.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({ cliente_id: cliente_id }),
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        alert('Usuario eliminado exitosamente');
                        location.reload();
                    } else {
                        alert('Error al eliminar el usuario: ' + data.message);
                    }
                })
                .catch(error => {
                    alert('Error al eliminar el usuario: ' + error.message);
                });
            }
        });
    });
});