document.addEventListener('DOMContentLoaded', function () {

    //-- PESOS --//
    // Torta Pesos
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
                },
                tooltip: {
                    callbacks: {
                        label: function (tooltipItem) {
                            const total = tooltipItem.dataset.data.reduce((acc, value) => acc + value, 0);
                            const currentValue = tooltipItem.raw;
                            const percentage = ((currentValue / total) * 100).toFixed(2).replace('.', ',');
                            return percentage + '%';
                        }
                    }
                }
            }
        }
    };

    const chartPesos = new Chart(
        document.getElementById('ChartPesos'),
        configPesos
    );
    // Fin Torta Pesos

    // Rendimiento Pesos
    const dataRendimientoPesos = {
        labels: ['Acciones', 'Cedears', 'Bonos', 'Fondos'],
        datasets: [{
            data: [
                parseFloat(document.getElementById('rendimiento_consolidado_acciones_pesos').textContent),
                parseFloat(document.getElementById('rendimiento_consolidado_cedear_pesos').textContent),
                parseFloat(document.getElementById('rendimiento_consolidado_bonos_pesos').textContent),
                parseFloat(document.getElementById('rendimiento_consolidado_fondos_pesos').textContent)
            ],
            backgroundColor: function(context) {
                const value = context.dataset.data[context.dataIndex];
                return value > 0 ? 'green' : 'red';
            },
            borderWidth: 1
        }]
    };

    const configRendimientoPesos = {
        type: 'bar',
        data: dataRendimientoPesos,
        options: {
            plugins: {
                legend: {
                    display: false // Ocultar la leyenda
                },
                tooltip: {
                    callbacks: {
                        label: function(tooltipItem) {
                            const value = tooltipItem.raw.toFixed(2).replace('.', ',').replace(/\B(?=(\d{3})+(?!\d))/g, ".");
                            return (tooltipItem.raw < 0 ? '$ ' : '$ ') + value;
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            const absValue = Math.abs(value);
                            const sign = value < 0 ? '-' : '';
                            return '$ ' + sign + (absValue / 1000) + 'k';
                        }
                    }
                }
            }
        }
    };

    const chartRendimientoPesos = new Chart(
        document.getElementById('ChartRendimientoPesos'),
        configRendimientoPesos
    );
    // Fin Rendimiento Pesos

    // Rentabilidad Pesos
    const dataRentPesos = {
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

    const configRentPesos = {
        type: 'bar',
        data: dataRentPesos,
        options: {
            plugins: {
                legend: {
                    display: false // Ocultar la leyenda
                },
                tooltip: {
                    callbacks: {
                        label: function(tooltipItem) {
                            const value = tooltipItem.raw.toFixed(2).replace('.', ',').replace(/\B(?=(\d{3})+(?!\d))/g, ".");
                            return value + '%';
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return value + '%';
                        }
                    }
                }
            }
        }
    };

    const chartRentPesos = new Chart(
        document.getElementById('ChartRentPesos'),
        configRentPesos
    );
    // Fin Rentabilidad Pesos
    //-- FIN PESOS --//

    //-- DOLARES --//
        // Torta Dolares
        const dataDolares = {
            labels: ['Acciones', 'Cedears', 'Bonos', 'Fondos', 'Efectivo'],
            datasets: [{
                data: [
                    parseFloat(document.getElementById('valor_actual_consolidado_acciones_dolares').textContent),
                    parseFloat(document.getElementById('valor_actual_consolidado_cedear_dolares').textContent),
                    parseFloat(document.getElementById('valor_actual_consolidado_bonos_dolares').textContent),
                    parseFloat(document.getElementById('valor_actual_consolidado_fondos_dolares').textContent),
                    parseFloat(document.getElementById('saldo_en_dolares').textContent)
                ],
                backgroundColor: ['#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF'],
            }]
        };
    
        const configDolares = {
            type: 'pie',
            data: dataDolares,
            options: {
                plugins: {
                    legend: {
                        position: 'left',
                        labels: {
                            font: {
                                size: 16 // Tamaño de la fuente de las etiquetas
                            }
                        }
                    },
                    tooltip: {
                        callbacks: {
                            label: function (tooltipItem) {
                                const total = tooltipItem.dataset.data.reduce((acc, value) => acc + value, 0);
                                const currentValue = tooltipItem.raw;
                                const percentage = ((currentValue / total) * 100).toFixed(2).replace('.', ',');
                                return percentage + '%';
                            }
                        }
                    }
                }
            }
        };
    
        const chartDolares = new Chart(
            document.getElementById('ChartDolares'),
            configDolares
        );
        // Fin Torta Dolares

    // Rendimiento Dólares
    const dataRendimientoDolares = {
        labels: ['Acciones', 'Cedears', 'Bonos', 'Fondos'],
        datasets: [{
            data: [
                parseFloat(document.getElementById('rendimiento_consolidado_acciones_dolares').textContent),
                parseFloat(document.getElementById('rendimiento_consolidado_cedear_dolares').textContent),
                parseFloat(document.getElementById('rendimiento_consolidado_bonos_dolares').textContent),
                parseFloat(document.getElementById('rendimiento_consolidado_fondos_dolares').textContent)
            ],
            backgroundColor: function(context) {
                const value = context.dataset.data[context.dataIndex];
                return value > 0 ? 'green' : 'red';
            },
            borderWidth: 1
        }]
    };

    const configRendimientoDolares = {
        type: 'bar',
        data: dataRendimientoDolares,
        options: {
            plugins: {
                legend: {
                    display: false // Ocultar la leyenda
                },
                tooltip: {
                    callbacks: {
                        label: function(tooltipItem) {
                            const value = tooltipItem.raw.toFixed(2).replace('.', ',').replace(/\B(?=(\d{3})+(?!\d))/g, ".");
                            return (tooltipItem.raw < 0 ? 'u$s ' : 'u$s ') + value;
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            const absValue = Math.abs(value);
                            const sign = value < 0 ? '-' : '';
                            return 'u$s ' + sign + absValue;
                        }
                    }
                }
            }
        }
    };

    const chartRendimientoDolares = new Chart(
        document.getElementById('ChartRendimientoDolares'),
        configRendimientoDolares
    );
    // Fin Rendimiento Dólares

    // Rentabilidad Dólares
    const dataRentDolares = {
        labels: ['Acciones', 'Cedears', 'Bonos', 'Fondos'],
        datasets: [{
            data: [
                parseFloat(document.getElementById('rentabilidad_consolidado_acciones_dolares').textContent),
                parseFloat(document.getElementById('rentabilidad_consolidado_cedear_dolares').textContent),
                parseFloat(document.getElementById('rentabilidad_consolidado_bonos_dolares').textContent),
                parseFloat(document.getElementById('rentabilidad_consolidado_fondos_dolares').textContent)
            ],
            backgroundColor: function(context) {
                const value = context.dataset.data[context.dataIndex];
                return value > 0 ? 'green' : 'red';
            },
            borderWidth: 1
        }]
    };

    const configRentDolares = {
        type: 'bar',
        data: dataRentDolares,
        options: {
            plugins: {
                legend: {
                    display: false // Ocultar la leyenda
                },
                tooltip: {
                    callbacks: {
                        label: function(tooltipItem) {
                            const value = tooltipItem.raw.toFixed(2).replace('.', ',').replace(/\B(?=(\d{3})+(?!\d))/g, ".");
                            return value + '%';
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return value + '%';
                        }
                    }
                }
            }
        }
    };

    const chartRentDolares = new Chart(
        document.getElementById('ChartRentDolares'),
        configRentDolares
    );
    // Fin Rentabilidad Dólares
    //-- FIN DOLARES --//
});