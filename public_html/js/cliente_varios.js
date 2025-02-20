        // Función para formatear números con separadores de miles y decimales
        function formatearNumero(numero) {
            return new Intl.NumberFormat('es-AR', {
                style: 'currency',
                currency: 'ARS'
            }).format(numero);
        }

        // Función para actualizar la tabla con los valores actuales de las acciones
        async function actualizarValoresAcciones() {
            const filas = document.querySelectorAll('#tabla-acciones-pesos tr');
            for (const fila of filas) {
                const ticker = fila.dataset.ticker;
                const valorActualTd = fila.querySelector('.valor-actual');
                try {
                    const response = await fetch(`../funciones/obtener_valor_accion.php?ticker=${ticker}`);
                    const data = await response.json();
                    if (data.valor) {
                        valorActualTd.innerText = formatearNumero(data.valor);
                    } else {
                        valorActualTd.innerText = 'Valor no disponible';
                    }
                } catch (error) {
                    console.error('Error al obtener el valor de la acción:', error);
                    valorActualTd.innerText = 'Error';
                }
            }
        }

        document.addEventListener('DOMContentLoaded', () => {
            actualizarValoresAcciones();
        });