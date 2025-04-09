document.addEventListener('DOMContentLoaded', function () {
    let tickerBonoSeleccionado = null;
    let botonBonoSeleccionado = null;

    document.addEventListener('click', function (e) {
        const eliminarBtn = e.target.closest('.eliminar');
        if (eliminarBtn && eliminarBtn.getAttribute('onclick')?.includes('eliminarBono')) {
            e.preventDefault();

            botonBonoSeleccionado = eliminarBtn;
            const fila = eliminarBtn.closest('tr');
            tickerBonoSeleccionado = fila?.getAttribute('data-ticker') || null;

            const nombreBono = fila?.querySelector('td:first-child')?.textContent?.trim() || 'este bono';

            // Mostrar nombre dinámicamente
            document.querySelector('#confirmDeleteModalBonos .modal-body p.text-center').textContent =
                `¿Estás seguro que querés eliminar ${nombreBono}?`;

            const modal = new bootstrap.Modal(document.getElementById('confirmDeleteModalBonos'));
            modal.show();
        }
    });

    document.getElementById('confirmDeleteBtnBonos').addEventListener('click', function () {
        if (!tickerBonoSeleccionado || !botonBonoSeleccionado) return;

        const cliente_id = document.querySelector('meta[name="cliente_id"]').getAttribute('content');
        const fila = botonBonoSeleccionado.closest('tr');

        const originalHTML = botonBonoSeleccionado.innerHTML;
        botonBonoSeleccionado.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> Eliminando...';
        botonBonoSeleccionado.classList.add('disabled');

        fetch('../funciones/eliminar_bonos.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ cliente_id, ticker: tickerBonoSeleccionado })
        })
        .then(res => res.json())
        .then(data => {
            if (data.status === 'success') {
                const modal = bootstrap.Modal.getInstance(document.getElementById('confirmDeleteModalBonos'));
                modal.hide();

                showAlert('Bono eliminado con éxito', 'success');
                fila.remove();
            } else {
                throw new Error(data.message || 'Error al eliminar');
            }
        })
        .catch(err => {
            showAlert(`Error: ${err.message}`, 'danger');
            botonBonoSeleccionado.innerHTML = originalHTML;
            botonBonoSeleccionado.classList.remove('disabled');
        })
        .finally(() => {
            tickerBonoSeleccionado = null;
            botonBonoSeleccionado = null;
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
