// filtro_tablas.js

import { initDataTable } from './datatables.js';

$(document).ready(function () {
    initDataTable('#tenencia_acciones_pesos');
    initDataTable('#tenencia_cedear_pesos');
    initDataTable('#tenencia_bonos_pesos');
    initDataTable('#tenencia_fondos_pesos');
    
    initDataTable('#tenencia_acciones_dolares');
    initDataTable('#tenencia_cedear_dolares');
    initDataTable('#tenencia_bonos_dolares');
    initDataTable('#tenencia_fondos_dolares');
});
