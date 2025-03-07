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
    
    // Cedear
    const btnCedearPesos = document.getElementById("btnCedearPesos");
    const btnCedearDolares = document.getElementById("btnCedearDolares");
    const tablaCedearPesos = document.getElementById("tablaCedearPesos");
    const tablaCedearDolares = document.getElementById("tablaCedearDolares");

    // Función para alternar entre las tablas
    btnCedearPesos.addEventListener("click", () => {
        btnCedearPesos.classList.add("active");
        btnCedearDolares.classList.remove("active");
        tablaCedearPesos.classList.remove("d-none");
        tablaCedearDolares.classList.add("d-none");
    });

    btnCedearDolares.addEventListener("click", () => {
        btnCedearDolares.classList.add("active");
        btnCedearPesos.classList.remove("active");
        tablaCedearDolares.classList.remove("d-none");
        tablaCedearPesos.classList.add("d-none");
    });
 
    // Bonos
    const btnBonosPesos = document.getElementById("btnBonosPesos");
    const btnBonosDolares = document.getElementById("btnBonosDolares");
    const tablaBonosPesos = document.getElementById("tablaBonosPesos");
    const tablaBonosDolares = document.getElementById("tablaBonosDolares");

    // Función para alternar entre las tablas
    btnBonosPesos.addEventListener("click", () => {
        btnBonosPesos.classList.add("active");
        btnBonosDolares.classList.remove("active");
        tablaBonosPesos.classList.remove("d-none");
        tablaBonosDolares.classList.add("d-none");
    });

    btnBonosDolares.addEventListener("click", () => {
        btnBonosDolares.classList.add("active");
        btnBonosPesos.classList.remove("active");
        tablaBonosDolares.classList.remove("d-none");
        tablaBonosPesos.classList.add("d-none");
    });
 
    // Fondos
    const btnFondosPesos = document.getElementById("btnFondosPesos");
    const btnFondosDolares = document.getElementById("btnFondosDolares");
    const tablaFondosPesos = document.getElementById("tablaFondosPesos");
    const tablaFondosDolares = document.getElementById("tablaFondosDolares");

    // Función para alternar entre las tablas
    btnFondosPesos.addEventListener("click", () => {
        btnFondosPesos.classList.add("active");
        btnFondosDolares.classList.remove("active");
        tablaFondosPesos.classList.remove("d-none");
        tablaFondosDolares.classList.add("d-none");
    });

    btnFondosDolares.addEventListener("click", () => {
        btnFondosDolares.classList.add("active");
        btnFondosPesos.classList.remove("active");
        tablaFondosDolares.classList.remove("d-none");
        tablaFondosPesos.classList.add("d-none");
    });
 
});