document.addEventListener("DOMContentLoaded", () => {
    
    // Resumen
    const btnResumenPesos = document.getElementById("btnResumenPesos");
    const btnResumenDolares = document.getElementById("btnResumenDolares");
    const tablaResumenPesos = document.getElementById("tablaResumenPesos");
    const tablaResumenDolares = document.getElementById("tablaResumenDolares");

    // Función para alternar entre las tablas
    btnResumenPesos.addEventListener("click", () => {
        btnResumenPesos.classList.add("active");
        btnResumenDolares.classList.remove("active");
        tablaResumenPesos.classList.remove("d-none");
        tablaResumenDolares.classList.add("d-none");
        // Reinitialize DataTable
        $('#completa_acciones_pesos').DataTable().columns.adjust().draw();
    });

    btnResumenDolares.addEventListener("click", () => {
        btnResumenDolares.classList.add("active");
        btnResumenPesos.classList.remove("active");
        tablaResumenDolares.classList.remove("d-none");
        tablaResumenPesos.classList.add("d-none");
        // Destroy and Reinitialize DataTable
        if ($.fn.DataTable.isDataTable('#completa_acciones_dolares')) {
            $('#completa_acciones_dolares').DataTable().destroy();
        }
        $('#completa_acciones_dolares').DataTable({
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.4/i18n/es-ES.json'
            },
            pageLength: 10,
            lengthMenu: [5, 10, 25, 50, 100],
            order: [[0, 'asc']],
            autoWidth: true,
            dom: '<"row mb-3"<"col-md-6 text-left"l><"col-md-6"f>>' +
                'rt' +
                '<"row mt-3"<"col-md-6 text-left"i><"col-md-6"p>>'
        });
        $('#completa_acciones_dolares').DataTable().columns.adjust().draw();
    });

    // Acciones
    const btnAccionesPesos = document.getElementById("btnAccionesPesos");
    const btnAccionesDolares = document.getElementById("btnAccionesDolares");
    const tablaAccionesPesos = document.getElementById("tablaAccionesPesos");
    const tablaAccionesDolares = document.getElementById("tablaAccionesDolares");

    // Función para alternar entre las tablas
    btnAccionesPesos.addEventListener("click", () => {
        btnAccionesPesos.classList.add("active");
        btnAccionesDolares.classList.remove("active");
        tablaAccionesPesos.classList.remove("d-none");
        tablaAccionesDolares.classList.add("d-none");
        // Reinitialize DataTable
        $('#completa_acciones_pesos').DataTable().columns.adjust().draw();
    });

    btnAccionesDolares.addEventListener("click", () => {
        btnAccionesDolares.classList.add("active");
        btnAccionesPesos.classList.remove("active");
        tablaAccionesDolares.classList.remove("d-none");
        tablaAccionesPesos.classList.add("d-none");
        // Destroy and Reinitialize DataTable
        if ($.fn.DataTable.isDataTable('#completa_acciones_dolares')) {
            $('#completa_acciones_dolares').DataTable().destroy();
        }
        $('#completa_acciones_dolares').DataTable({
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.4/i18n/es-ES.json'
            },
            pageLength: 10,
            lengthMenu: [5, 10, 25, 50, 100],
            order: [[0, 'asc']],
            autoWidth: true,
            dom: '<"row mb-3"<"col-md-6 text-left"l><"col-md-6"f>>' +
                'rt' +
                '<"row mt-3"<"col-md-6 text-left"i><"col-md-6"p>>'
        });
        $('#completa_acciones_dolares').DataTable().columns.adjust().draw();
    });

    // Cedear
    const btnCedearPesos = document.getElementById("btnCedearPesos");
    const btnCedearDolares = document.getElementById("btnCedearDolares");
    const tablaCedearPesos = document.getElementById("tablaCedearPesos");
    const tablaCedearDolares = document.getElementById("tablaCedearDolares");

    btnCedearPesos.addEventListener("click", () => {
        btnCedearPesos.classList.add("active");
        btnCedearDolares.classList.remove("active");
        tablaCedearPesos.classList.remove("d-none");
        tablaCedearDolares.classList.add("d-none");
        // Reinitialize DataTable
        $('#completa_cedear_pesos').DataTable().columns.adjust().draw();
    });

    btnCedearDolares.addEventListener("click", () => {
        btnCedearDolares.classList.add("active");
        btnCedearPesos.classList.remove("active");
        tablaCedearDolares.classList.remove("d-none");
        tablaCedearPesos.classList.add("d-none");
        // Destroy and Reinitialize DataTable
        if ($.fn.DataTable.isDataTable('#completa_cedear_dolares')) {
            $('#completa_cedear_dolares').DataTable().destroy();
        }
        $('#completa_cedear_dolares').DataTable({
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.4/i18n/es-ES.json'
            },
            pageLength: 10,
            lengthMenu: [5, 10, 25, 50, 100],
            order: [[0, 'asc']],
            autoWidth: true,
            dom: '<"row mb-3"<"col-md-6 text-left"l><"col-md-6"f>>' +
                'rt' +
                '<"row mt-3"<"col-md-6 text-left"i><"col-md-6"p>>'
        });
        $('#completa_cedear_dolares').DataTable().columns.adjust().draw();
    });

    // Bonos
    const btnBonosPesos = document.getElementById("btnBonosPesos");
    const btnBonosDolares = document.getElementById("btnBonosDolares");
    const tablaBonosPesos = document.getElementById("tablaBonosPesos");
    const tablaBonosDolares = document.getElementById("tablaBonosDolares");

    btnBonosPesos.addEventListener("click", () => {
        btnBonosPesos.classList.add("active");
        btnBonosDolares.classList.remove("active");
        tablaBonosPesos.classList.remove("d-none");
        tablaBonosDolares.classList.add("d-none");
        // Reinitialize DataTable
        $('#completa_bonos_pesos').DataTable().columns.adjust().draw();
    });

    btnBonosDolares.addEventListener("click", () => {
        btnBonosDolares.classList.add("active");
        btnBonosPesos.classList.remove("active");
        tablaBonosDolares.classList.remove("d-none");
        tablaBonosPesos.classList.add("d-none");
        // Destroy and Reinitialize DataTable
        if ($.fn.DataTable.isDataTable('#completa_bonos_dolares')) {
            $('#completa_bonos_dolares').DataTable().destroy();
        }
        $('#completa_bonos_dolares').DataTable({
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.4/i18n/es-ES.json'
            },
            pageLength: 10,
            lengthMenu: [5, 10, 25, 50, 100],
            order: [[0, 'asc']],
            autoWidth: true,
            dom: '<"row mb-3"<"col-md-6 text-left"l><"col-md-6"f>>' +
                'rt' +
                '<"row mt-3"<"col-md-6 text-left"i><"col-md-6"p>>'
        });
        $('#completa_bonos_dolares').DataTable().columns.adjust().draw();
    });

    // Fondos
    const btnFondosPesos = document.getElementById("btnFondosPesos");
    const btnFondosDolares = document.getElementById("btnFondosDolares");
    const tablaFondosPesos = document.getElementById("tablaFondosPesos");
    const tablaFondosDolares = document.getElementById("tablaFondosDolares");

    btnFondosPesos.addEventListener("click", () => {
        btnFondosPesos.classList.add("active");
        btnFondosDolares.classList.remove("active");
        tablaFondosPesos.classList.remove("d-none");
        tablaFondosDolares.classList.add("d-none");
        // Reinitialize DataTable
        $('#completa_fondos_pesos').DataTable().columns.adjust().draw();
    });

    btnFondosDolares.addEventListener("click", () => {
        btnFondosDolares.classList.add("active");
        btnFondosPesos.classList.remove("active");
        tablaFondosDolares.classList.remove("d-none");
        tablaFondosPesos.classList.add("d-none");
        // Destroy and Reinitialize DataTable
        if ($.fn.DataTable.isDataTable('#completa_fondos_dolares')) {
            $('#completa_fondos_dolares').DataTable().destroy();
        }
        $('#completa_fondos_dolares').DataTable({
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.4/i18n/es-ES.json'
            },
            pageLength: 10,
            lengthMenu: [5, 10, 25, 50, 100],
            order: [[0, 'asc']],
            autoWidth: true,
            dom: '<"row mb-3"<"col-md-6 text-left"l><"col-md-6"f>>' +
                'rt' +
                '<"row mt-3"<"col-md-6 text-left"i><"col-md-6"p>>'
        });
        $('#completa_fondos_dolares').DataTable().columns.adjust().draw();
    });
});