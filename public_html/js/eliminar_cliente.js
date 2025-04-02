document.addEventListener('DOMContentLoaded', function() {
    // Event delegation para manejar elementos dinámicos del dropdown
    document.body.addEventListener('click', function(e) {
        const eliminarBtn = e.target.closest('.eliminar');
        
        if (eliminarBtn) {
            e.preventDefault();
            const clienteId = eliminarBtn.dataset.clienteId;
            
            // Mostrar confirmación con estilo Bootstrap
            const modal = new bootstrap.Modal(document.getElementById('confirmarEliminacion'));
            document.getElementById('confirmarEliminacion').querySelector('.btn-confirmar').onclick = function() {
                eliminarCliente(clienteId, eliminarBtn);
                modal.hide();
            };
            modal.show();
        }
    });

    // Función para manejar la eliminación
    function eliminarCliente(clienteId, boton) {
        // Feedback visual
        const originalHTML = boton.innerHTML;
        boton.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Eliminando...';
        boton.classList.add('disabled');

        fetch('../../funciones/eliminar_cliente.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ cliente_id: clienteId })
        })
        .then(handleResponse)
        .then(data => {
            if (data.status === 'success') {
                mostrarToast('Cliente eliminado correctamente', 'success');
                setTimeout(() => location.reload(), 1500);
            } else {
                throw new Error(data.message || 'Error al eliminar');
            }
        })
        .catch(error => {
            mostrarToast(error.message, 'danger');
            boton.innerHTML = originalHTML;
            boton.classList.remove('disabled');
        });
    }

    // Manejo de respuesta
    function handleResponse(response) {
        if (!response.ok) throw new Error('Error en la red');
        return response.json();
    }

    // Mostrar notificación Toast de Bootstrap
    function mostrarToast(mensaje, tipo = 'success') {
        const toastContainer = document.getElementById('toastContainer');
        const toastId = 'toast-' + Date.now();
        
        toastContainer.insertAdjacentHTML('beforeend', `
            <div id="${toastId}" class="toast align-items-center text-white bg-${tipo} border-0" role="alert" aria-live="assertive" aria-atomic="true">
                <div class="d-flex">
                    <div class="toast-body">${mensaje}</div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
            </div>
        `);
        
        const toast = new bootstrap.Toast(document.getElementById(toastId));
        toast.show();
        
        setTimeout(() => {
            toast.dispose();
            document.getElementById(toastId).remove();
        }, 5000);
    }
});