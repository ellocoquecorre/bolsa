document.addEventListener('DOMContentLoaded', function () {
    let tickerSeleccionado = null;
    let botonSeleccionado = null;

    // Detectar clic en el botón "Eliminar"
    document.addEventListener('click', function (e) {
        const eliminarBtn = e.target.closest('.eliminar');
        if (eliminarBtn && eliminarBtn.getAttribute('onclick')?.includes('eliminarAccion')) {
            e.preventDefault();

            botonSeleccionado = eliminarBtn;
            const fila = eliminarBtn.closest('tr');
            tickerSeleccionado = fila?.getAttribute('data-ticker') || null;

            const nombreAccion = fila?.querySelector('td:first-child')?.textContent?.trim() || 'esta acción';

            // Mostrar nombre dinámicamente
            document.querySelector('#confirmDeleteModalAcciones .modal-body p.text-center').textContent =
                `¿Estás seguro que querés eliminar ${nombreAccion}?`;

            const modal = new bootstrap.Modal(document.getElementById('confirmDeleteModalAcciones'));
            modal.show();
        }
    });

    // Confirmar eliminación
    document.getElementById('confirmDeleteBtnAcciones').addEventListener('click', function () {
        if (!tickerSeleccionado || !botonSeleccionado) return;

        const cliente_id = document.querySelector('meta[name="cliente_id"]').getAttribute('content');
        const fila = botonSeleccionado.closest('tr');

        const originalHTML = botonSeleccionado.innerHTML;
        botonSeleccionado.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> Eliminando...';
        botonSeleccionado.classList.add('disabled');

        fetch('../funciones/eliminar_acciones.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ cliente_id, ticker: tickerSeleccionado })
        })
        .then(res => res.json())
        .then(data => {
            if (data.status === 'success') {
                const modal = bootstrap.Modal.getInstance(document.getElementById('confirmDeleteModalAcciones'));
                modal.hide();

                showAlert('Acción eliminada con éxito', 'success');
                fila.remove(); // Eliminar fila visualmente
            } else {
                throw new Error(data.message || 'Error al eliminar');
            }
        })
        .catch(err => {
            showAlert(`Error: ${err.message}`, 'danger');
            botonSeleccionado.innerHTML = originalHTML;
            botonSeleccionado.classList.remove('disabled');
        })
        .finally(() => {
            tickerSeleccionado = null;
            botonSeleccionado = null;
        });
    });

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
        setTimeout(() => alertDiv.remove(), 4000);
    }
});
