document.addEventListener('DOMContentLoaded', function () {
    let tickerCedearSeleccionado = null;
    let botonCedearSeleccionado = null;

    document.addEventListener('click', function (e) {
        const eliminarBtn = e.target.closest('.eliminar');
        if (eliminarBtn && eliminarBtn.getAttribute('onclick')?.includes('eliminarCedear')) {
            e.preventDefault();

            botonCedearSeleccionado = eliminarBtn;
            const fila = eliminarBtn.closest('tr');
            tickerCedearSeleccionado = fila?.getAttribute('data-ticker') || null;

            const nombreCedear = fila?.querySelector('td:first-child')?.textContent?.trim() || 'este Cedear';

            // Mostrar nombre dinámicamente
            document.querySelector('#confirmDeleteModalCedears .modal-body p.text-center').textContent =
                `¿Estás seguro que querés eliminar ${nombreCedear}?`;

            const modal = new bootstrap.Modal(document.getElementById('confirmDeleteModalCedears'));
            modal.show();
        }
    });

    document.getElementById('confirmDeleteBtnCedears').addEventListener('click', function () {
        if (!tickerCedearSeleccionado || !botonCedearSeleccionado) return;

        const cliente_id = document.querySelector('meta[name="cliente_id"]').getAttribute('content');
        const fila = botonCedearSeleccionado.closest('tr');

        const originalHTML = botonCedearSeleccionado.innerHTML;
        botonCedearSeleccionado.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> Eliminando...';
        botonCedearSeleccionado.classList.add('disabled');

        fetch('../funciones/eliminar_cedears.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ cliente_id, ticker: tickerCedearSeleccionado })
        })
        .then(res => res.json())
        .then(data => {
            if (data.status === 'success') {
                const modal = bootstrap.Modal.getInstance(document.getElementById('confirmDeleteModalCedears'));
                modal.hide();

                showAlert('Cedear eliminado con éxito', 'success');
                fila.remove();
            } else {
                throw new Error(data.message || 'Error al eliminar');
            }
        })
        .catch(err => {
            showAlert(`Error: ${err.message}`, 'danger');
            botonCedearSeleccionado.innerHTML = originalHTML;
            botonCedearSeleccionado.classList.remove('disabled');
        })
        .finally(() => {
            tickerCedearSeleccionado = null;
            botonCedearSeleccionado = null;
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
