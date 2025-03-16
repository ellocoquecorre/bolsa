document.addEventListener("DOMContentLoaded", function(){
    const ctx = document.getElementById('pieChart2').getContext('2d');
    const data = {
        labels: ['Acciones', 'Cedears', 'Bonos', 'Fondos'],
        datasets: [{
            data: [
                valor_actual_consolidado_acciones_dolares,
                valor_actual_consolidado_cedear_dolares,
                valor_actual_consolidado_bonos_dolares,
                valor_actual_consolidado_fondos_dolares
            ],
            backgroundColor: [
                'rgba(255, 99, 132, 0.2)',
                'rgba(54, 162, 235, 0.2)',
                'rgba(255, 206, 86, 0.2)',
                'rgba(75, 192, 192, 0.2)'
            ],
            borderColor: [
                'rgba(255, 99, 132, 1)',
                'rgba(54, 162, 235, 1)',
                'rgba(255, 206, 86, 1)',
                'rgba(75, 192, 192, 1)'
            ],
            borderWidth: 1
        }]
    };

    const config = {
        type: 'pie',
        data: data,
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'top',
                },
                title: {
                    display: true,
                    text: 'Distribución de Inversiones'
                }
            }
        },
    };

    const pieChart = new Chart(ctx, config);
});