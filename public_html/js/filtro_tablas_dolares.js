$(document).ready(function() {
    // Inicializar DataTables en la tabla con 12 columnas
    function initDataTable12(tableId) {
        $(tableId).DataTable({
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.4/i18n/es-ES.json'  // Configuración de idioma en español
            },
            pageLength: 10,  // Número de filas por página por defecto
            lengthMenu: [5, 10, 25, 50, 100],  // Opciones de cantidad de filas por página
            order: [[0, 'asc']],  // Ordenar por la tercera columna (índice 2) de manera ascendente
            autoWidth: true,  // Ajuste automático de anchos de columnas
            dom: '<"row mb-3"<"col-md-6"l><"col-md-6"f>>' +  // Fila superior: longitud y búsqueda
                 'rt' +  // Cuerpo de la tabla
                 '<"row mt-3"<"col-md-6"i><"col-md-6"p>>'  // Fila inferior: info y paginación
        });
    }

    // Inicializar las tablas con 12 columnas
    initDataTable12('#completa_acciones_dolares');
});
