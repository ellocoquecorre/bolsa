document.addEventListener('DOMContentLoaded', function () {
    let tickerFondoSeleccionado = null;
    let botonFondoSeleccionado = null;

    document.addEventListener('click', function (e) {
        const eliminarBtn = e.target.closest('.eliminar');
        if (eliminarBtn && eliminarBtn.getAttribute('onclick')?.includes('eliminarFondo')) {
            e.preventDefault();

            botonFondoSeleccionado = eliminarBtn;
            const fila = eliminarBtn.closest('tr');
            tickerFondoSeleccionado = fila?.getAttribute('data-ticker') || null;

            const nombreFondo = fila?.querySelector('td:first-child')?.textContent?.trim() || 'este fondo';

            document.querySelector('#confirmDeleteModalFondos .modal-body p.text-center').textContent =
                `¿Estás seguro que querés eliminar ${nombreFondo}?`;

            const modal = new bootstrap.Modal(document.getElementById('confirmDeleteModalFondos'));
            modal.show();
        }
    });

    document.getElementById('confirmDeleteBtnFondos').addEventListener('click', function () {
        if (!tickerFondoSeleccionado || !botonFondoSeleccionado) return;

        const cliente_id = document.querySelector('meta[name="cliente_id"]').getAttribute('content');
        const fila = botonFondoSeleccionado.closest('tr');

        const originalHTML = botonFondoSeleccionado.innerHTML;
        botonFondoSeleccionado.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> Eliminando...';
        botonFondoSeleccionado.classList.add('disabled');

        fetch('../funciones/eliminar_fondos.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ cliente_id, ticker: tickerFondoSeleccionado })
        })
        .then(res => res.json())
        .then(data => {
            if (data.status === 'success') {
                const modal = bootstrap.Modal.getInstance(document.getElementById('confirmDeleteModalFondos'));
                modal.hide();

                showAlert('Fondo eliminado con éxito', 'success');
                fila.remove();
            } else {
                throw new Error(data.message || 'Error al eliminar');
            }
        })
        .catch(err => {
            showAlert(`Error: ${err.message}`, 'danger');
            botonFondoSeleccionado.innerHTML = originalHTML;
            botonFondoSeleccionado.classList.remove('disabled');
        })
        .finally(() => {
            tickerFondoSeleccionado = null;
            botonFondoSeleccionado = null;
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
