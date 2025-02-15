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
    });

    btnResumenDolares.addEventListener("click", () => {
        btnResumenDolares.classList.add("active");
        btnResumenPesos.classList.remove("active");
        tablaResumenDolares.classList.remove("d-none");
        tablaResumenPesos.classList.add("d-none");
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
    });

    btnAccionesDolares.addEventListener("click", () => {
        btnAccionesDolares.classList.add("active");
        btnAccionesPesos.classList.remove("active");
        tablaAccionesDolares.classList.remove("d-none");
        tablaAccionesPesos.classList.add("d-none");
    });
    
});