$(document).ready(function() {
    // Función para inicializar DataTables
    function initDataTable(tableId, orderColumn = 0) {
        $(tableId).DataTable({
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.4/i18n/es-ES.json'  // Configuración de idioma en español
            },
            pageLength: 10,  // Número de filas por página por defecto
            lengthMenu: [5, 10, 25, 50, 100],  // Opciones de cantidad de filas por página
            order: [[orderColumn, 'asc']],  // Ordenar por la columna especificada
            autoWidth: true, // Ajuste automático de anchos de columnas
            dom: '<"row mb-3"<"col-md-6 text-left"l><"col-md-6"f>>' +  // Fila superior: longitud y búsqueda
                 'rt' +  // Cuerpo de la tabla
                 '<"row mt-3"<"col-md-6 text-left"i><"col-md-6"p>>'  // Fila inferior: info y paginación
        });
    }

    // Inicializar tabla de 10 columnas
    initDataTable('#completa_acciones_pesos', 0);   // Para la tabla #completa_acciones_pesos, orden por la tercera columna
});
