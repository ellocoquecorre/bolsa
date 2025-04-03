document.addEventListener('DOMContentLoaded', function() {
    // Event delegation para manejar clicks
    document.addEventListener('click', function(e) {
        if (e.target.closest('.eliminar')) {
            e.preventDefault();
            const boton = e.target.closest('.eliminar');
            const clienteId = boton.dataset.clienteId;
            
            // Mostrar modal de confirmación de Bootstrap
            const confirmModal = new bootstrap.Modal(document.getElementById('confirmDeleteModal'));
            const confirmBtn = document.getElementById('confirmDeleteBtn');
            
            // Configurar el modal
            confirmBtn.onclick = function() {
                eliminarCliente(clienteId, boton);
                confirmModal.hide();
            };
            
            confirmModal.show();
        }
    });

    function eliminarCliente(clienteId, boton) {
        // Feedback visual
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
                showAlert('Cliente eliminado correctamente', 'success');
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

    // Mostrar alerta centrada
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
        
        // Auto-eliminar después de 5 segundos
        setTimeout(() => {
            alertDiv.remove();
        }, 5000);
    }
});