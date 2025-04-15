// botones_pesos_dolares.js

import { initDataTable } from './datatables.js';

document.addEventListener("DOMContentLoaded", () => {
    const columnasDolares = [2, 3, 4, 5, 6, 7, 8, 11];

    // Resumen
    const btnResumenPesos = document.getElementById("btnResumenPesos");
    const btnResumenDolares = document.getElementById("btnResumenDolares");
    const tablaResumenPesos = document.getElementById("tablaResumenPesos");
    const tablaResumenDolares = document.getElementById("tablaResumenDolares");

    btnResumenPesos.addEventListener("click", () => {
        btnResumenPesos.classList.add("active");
        btnResumenDolares.classList.remove("active");
        tablaResumenPesos.classList.remove("d-none");
        tablaResumenDolares.classList.add("d-none");
        $('#tenencia_acciones_pesos').DataTable().columns.adjust().draw();
    });

    btnResumenDolares.addEventListener("click", () => {
        btnResumenDolares.classList.add("active");
        btnResumenPesos.classList.remove("active");
        tablaResumenDolares.classList.remove("d-none");
        tablaResumenPesos.classList.add("d-none");
        initDataTable('#tenencia_acciones_dolares', 0, columnasDolares);
    });

    // Acciones
    const btnAccionesPesos = document.getElementById("btnAccionesPesos");
    const btnAccionesDolares = document.getElementById("btnAccionesDolares");
    const tablaAccionesPesos = document.getElementById("tablaAccionesPesos");
    const tablaAccionesDolares = document.getElementById("tablaAccionesDolares");

    btnAccionesPesos.addEventListener("click", () => {
        btnAccionesPesos.classList.add("active");
        btnAccionesDolares.classList.remove("active");
        tablaAccionesPesos.classList.remove("d-none");
        tablaAccionesDolares.classList.add("d-none");
        $('#tenencia_acciones_pesos').DataTable().columns.adjust().draw();
    });

    btnAccionesDolares.addEventListener("click", () => {
        btnAccionesDolares.classList.add("active");
        btnAccionesPesos.classList.remove("active");
        tablaAccionesDolares.classList.remove("d-none");
        tablaAccionesPesos.classList.add("d-none");
        initDataTable('#tenencia_acciones_dolares', 0, columnasDolares);
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
        $('#tenencia_cedear_pesos').DataTable().columns.adjust().draw();
    });

    btnCedearDolares.addEventListener("click", () => {
        btnCedearDolares.classList.add("active");
        btnCedearPesos.classList.remove("active");
        tablaCedearDolares.classList.remove("d-none");
        tablaCedearPesos.classList.add("d-none");
        initDataTable('#tenencia_cedear_dolares', 0, columnasDolares);
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
        $('#tenencia_bonos_pesos').DataTable().columns.adjust().draw();
    });

    btnBonosDolares.addEventListener("click", () => {
        btnBonosDolares.classList.add("active");
        btnBonosPesos.classList.remove("active");
        tablaBonosDolares.classList.remove("d-none");
        tablaBonosPesos.classList.add("d-none");
        initDataTable('#tenencia_bonos_dolares', 0, columnasDolares);
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
        $('#tenencia_fondos_pesos').DataTable().columns.adjust().draw();
    });

    btnFondosDolares.addEventListener("click", () => {
        btnFondosDolares.classList.add("active");
        btnFondosPesos.classList.remove("active");
        tablaFondosDolares.classList.remove("d-none");
        tablaFondosPesos.classList.add("d-none");
        initDataTable('#tenencia_fondos_dolares', 0, columnasDolares);
    });
});
