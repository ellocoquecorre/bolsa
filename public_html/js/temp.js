const dataRendiPesos = {
    labels: ['Acciones', 'Cedears', 'Bonos', 'Fondos'],
    datasets: [{
        data: [
            parseFloat(document.getElementById('rentabilidad_consolidado_acciones_pesos').textContent),
            parseFloat(document.getElementById('rentabilidad_consolidado_cedear_pesos').textContent),
            parseFloat(document.getElementById('rentabilidad_consolidado_bonos_pesos').textContent),
            parseFloat(document.getElementById('rentabilidad_consolidado_fondos_pesos').textContent)
        ],
        backgroundColor: function(context) {
            const value = context.dataset.data[context.dataIndex];
            return value > 0 ? 'green' : 'red';
        },
        borderWidth: 1
    }]
};

const configRendiPesos = {
    type: 'bar',
    data: dataRendiPesos,
    options: {
        plugins: {
            legend: {
                display: false // Ocultar la leyenda
            }
        },
        scales: {
            y: {
                beginAtZero: true
            }
        }
    }
};

const chartRendiPesos = new Chart(
    document.getElementById('ChartRendiPesos'),
    configRendiPesos
);
