document.addEventListener('DOMContentLoaded', function () {
    let clienteIdSeleccionado = null;
    let botonSeleccionado = null;

    // Escuchamos los clics en todos los botones de "Eliminar cliente"
    document.addEventListener('click', function (e) {
        const eliminarBtn = e.target.closest('.eliminar');
        if (eliminarBtn) {
            e.preventDefault();

            clienteIdSeleccionado = eliminarBtn.dataset.clienteId;
            botonSeleccionado = eliminarBtn;

            // Intentamos extraer nombre y apellido desde la fila
            const fila = eliminarBtn.closest('tr');
            const nombreCompleto = fila?.querySelector('td:first-child')?.textContent?.trim() || 'este cliente';

            // Seteamos el texto del modal
            document.querySelector('#confirmDeleteModal .modal-body p.text-center').textContent =
                `¿Estás seguro que querés eliminar a ${nombreCompleto}?`;

            const confirmModal = new bootstrap.Modal(document.getElementById('confirmDeleteModal'));
            confirmModal.show();
        }
    });

    // Confirmar eliminación
    const confirmBtn = document.getElementById('confirmDeleteBtn');
    confirmBtn.addEventListener('click', function () {
        if (clienteIdSeleccionado && botonSeleccionado) {
            eliminarCliente(clienteIdSeleccionado, botonSeleccionado);
        }

        // Limpiar variables
        clienteIdSeleccionado = null;
        botonSeleccionado = null;
    });

    // También limpiamos si cierran el modal sin confirmar
    const cancelarBtn = document.querySelector('#confirmDeleteModal .cancelar');
    if (cancelarBtn) {
        cancelarBtn.addEventListener('click', () => {
            clienteIdSeleccionado = null;
            botonSeleccionado = null;
        });
    }

    function eliminarCliente(clienteId, boton) {
        const originalHTML = boton.innerHTML;
        boton.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> Eliminando...';
        boton.classList.add('disabled');

        fetch(`../funciones/eliminar_cliente.php`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ cliente_id: clienteId })
        })
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            if (data.status === 'success') {
                showAlert('Cliente eliminado con éxito', 'success');
                setTimeout(() => location.reload(), 1500);
            } else {
                throw new Error(data.message || 'Error al eliminar cliente');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showAlert(`Error al eliminar: ${error.message}`, 'danger');
            boton.innerHTML = originalHTML;
            boton.classList.remove('disabled');
        });
    }

    function showAlert(message, type = 'success') {
        const alertDiv = document.createElement('div');
        alertDiv.className = `alert alert-${type} alert-dismissible fade show text-center`;
        alertDiv.style.position = 'fixed';
        alertDiv.style.top = '20px';
        alertDiv.style.left = '50%';
        alertDiv.style.transform = 'translateX(-50%)';
        alertDiv.style.zIndex = '9999';
        alertDiv.style.minWidth = '300px';
        alertDiv.innerHTML = `
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        `;

        document.body.appendChild(alertDiv);

        setTimeout(() => {
            alertDiv.remove();
        }, 5000);
    }
});
