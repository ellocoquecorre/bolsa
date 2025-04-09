// filtro_tablas.js

import { initDataTable } from './datatables.js';

$(document).ready(function () {
    initDataTable('#completa_acciones_pesos', 0);
    initDataTable('#completa_acciones_dolares', 0);
    initDataTable('#completa_cedear_pesos', 0);
    initDataTable('#completa_cedear_dolares', 0);
    initDataTable('#completa_bonos_pesos', 0);
    initDataTable('#completa_bonos_dolares', 0);
    initDataTable('#completa_fondos_pesos', 0);
    initDataTable('#completa_fondos_dolares', 0);
});
