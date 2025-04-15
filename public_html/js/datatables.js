// datatables.js

export function initDataTable(tableId, orderColumn = 0, nonOrderableColumns = []) {
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
            { orderable: false, targets: nonOrderableColumns }
        ],
        dom: '<"row mb-3 d-flex align-items-baseline justify-content-center"<"col-md-4"f><"col-md-4"l><"col-md-4"i>>' +
             'rt' +
             '<"row mt-3 mb-3"<"col-md-12 text-center"p>>'
    });
}
