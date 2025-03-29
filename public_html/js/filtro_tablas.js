$(document).ready(function() {
    // Función para inicializar DataTables
    function initDataTable(tableId, orderColumn = 2) {
        $(tableId).DataTable({
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.4/i18n/es-ES.json'  // Configuración de idioma en español
            },
            pageLength: 10,  // Número de filas por página por defecto
            lengthMenu: [5, 10, 25, 50, 100],  // Opciones de cantidad de filas por página
            order: [[orderColumn, 'asc']],  // Ordenar por la columna especificada
            autoWidth: true, // Ajuste automático de anchos de columnas
            dom: '<"row mb-3 d-flex align-items-baseline justify-content-center"<"col-md-4"f><"col-md-4"l><"col-md-4"i>>' +  // búsqueda-longitud-info
                 'rt' +  // Cuerpo de la tabla
                 '<"row mt-3  mb-3"<"col-md-12 text-center"p>>'  // paginación
        });
    }

    // Inicializar ambas tablas
    initDataTable('#completa_acciones_pesos', 0);
    initDataTable('#completa_acciones_dolares', 0);
    initDataTable('#completa_cedear_pesos', 0);
    initDataTable('#completa_cedear_dolares', 0);
    initDataTable('#completa_bonos_pesos', 0);
    initDataTable('#completa_bonos_dolares', 0);
    initDataTable('#completa_fondos_pesos', 0);
    initDataTable('#completa_fondos_dolares', 0);
});
