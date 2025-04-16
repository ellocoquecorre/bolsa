// filtro_tablas.js

import { initDataTable } from './datatables.js';

$(document).ready(function () {
    // Tablas cliente
    initDataTable('#tenencia_acciones_pesos');
    initDataTable('#tenencia_cedear_pesos');
    initDataTable('#tenencia_bonos_pesos');
    initDataTable('#tenencia_fondos_pesos');

    initDataTable('#tenencia_acciones_dolares');
    initDataTable('#tenencia_cedear_dolares');
    initDataTable('#tenencia_bonos_dolares');
    initDataTable('#tenencia_fondos_dolares');

    // Tablas historial
    initDataTable('#historial_acciones_pesos');
    initDataTable('#historial_cedear_pesos');
    initDataTable('#historial_bonos_pesos');
    initDataTable('#historial_fondos_pesos');

    initDataTable('#historial_acciones_dolares');
    initDataTable('#historial_cedear_dolares');
    initDataTable('#historial_bonos_dolares');
    initDataTable('#historial_fondos_dolares');
});
