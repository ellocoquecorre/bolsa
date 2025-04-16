// datatables.js

export function initDataTable(tableId, orderColumn = 0) {
    // Columnas que NO se pueden ordenar, por tabla
    const columnasNoOrdenables = {
        // Cliente
        '#tenencia_acciones_pesos': [2, 3, 4, 5, 6],
        '#tenencia_cedear_pesos': [2, 3, 4, 5, 6],
        '#tenencia_bonos_pesos': [2, 3, 4, 5, 6],
        '#tenencia_fondos_pesos': [2, 3, 4, 5, 6],
        '#tenencia_acciones_dolares': [2, 3, 4, 5, 6, 7, 8],
        '#tenencia_cedear_dolares': [2, 3, 4, 5, 6, 7, 8],
        '#tenencia_bonos_dolares': [2, 3, 4, 5, 6, 7, 8],
        '#tenencia_fondos_dolares': [2, 3, 4, 5, 6, 7, 8],

        // Historial
        '#historial_acciones_pesos': [1, 3, 4, 6, 7],
        '#historial_cedear_pesos': [1, 3, 4, 6, 7],
        '#historial_bonos_pesos': [1, 3, 4, 6, 7],
        '#historial_fondos_pesos': [1, 3, 4, 6, 7],
        '#historial_acciones_dolares': [1, 3, 4, 5, 7, 8, 9],
        '#historial_cedear_dolares': [1, 3, 4, 5, 7, 8, 9],
        '#historial_bonos_dolares': [1, 3, 4, 5, 7, 8, 9],
        '#historial_fondos_dolares': [1, 3, 4, 5, 7, 8, 9]
    };

    if ($.fn.DataTable.isDataTable(tableId)) {
        $(tableId).DataTable().destroy();
    }

    $(tableId).DataTable({
        language: {
            url: '//cdn.datatables.net/plug-ins/1.13.4/i18n/es-ES.json'
        },
        pageLength: 10,
        lengthMenu: [5, 10, 25, 50, 100],
        order: [[orderColumn, 'asc']],
        autoWidth: true,
        columnDefs: [
            {
                orderable: false,
                targets: columnasNoOrdenables[tableId] || []
            }
        ],
        dom: '<"row mb-3 d-flex align-items-baseline justify-content-center"<"col-md-4"f><"col-md-4"l><"col-md-4"i>>' +
             'rt' +
             '<"row mt-3 mb-3"<"col-md-12 text-center"p>>'
    });
}