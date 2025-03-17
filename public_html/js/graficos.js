document.addEventListener('DOMContentLoaded', function () {
    // Datos para el gráfico en pesos
    const dataPesos = {
        labels: ['Acciones', 'Cedears', 'Bonos', 'Fondos', 'Efectivo'],
        datasets: [{
            data: [
                parseFloat(document.getElementById('valor_actual_consolidado_acciones_pesos').textContent),
                parseFloat(document.getElementById('valor_actual_consolidado_cedear_pesos').textContent),
                parseFloat(document.getElementById('valor_actual_consolidado_bonos_pesos').textContent),
                parseFloat(document.getElementById('valor_actual_consolidado_fondos_pesos').textContent),
                parseFloat(document.getElementById('saldo_en_pesos').textContent)
            ],
            backgroundColor: ['#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF'],
        }]
    };

    // Configuración para el gráfico en pesos
    const configPesos = {
        type: 'pie',
        data: dataPesos,
        options: {
            plugins: {
                legend: {
                    position: 'left',
                    labels: {
                        font: {
                            size: 16 // Tamaño de la fuente de las etiquetas
                        }
                    }
                }
            }
        }
    };

    // Renderizar el gráfico en pesos
    const chartPesos = new Chart(
        document.getElementById('ChartPesos'),
        configPesos
    );

// Gráfico Rentabilidad Pesos


// Gráfico Rentabilidad Dólares


});