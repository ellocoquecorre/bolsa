// filtro_tablas.js

import { initDataTable } from './datatables.js';

$(document).ready(function () {
    // Tablas que solo permiten orden en columnas 0, 1, 7 y 8
    const columnasPesos = [2, 3, 4, 5, 6, 9];
    const columnasDolares = [2, 3, 4, 5, 6, 7, 8, 11];

    initDataTable('#completa_acciones_pesos', 0, columnasPesos);
    initDataTable('#completa_cedear_pesos', 0, columnasPesos);
    initDataTable('#completa_bonos_pesos', 0, columnasPesos);
    initDataTable('#completa_fondos_pesos', 0, columnasPesos);    

    // Las demás tablas siguen con orden por defecto (solo columna 0, sin restricciones)
    initDataTable('#completa_acciones_dolares', 0, columnasDolares);
    initDataTable('#completa_cedear_dolares', 0, columnasDolares);
    initDataTable('#completa_bonos_dolares', 0, columnasDolares);
    initDataTable('#completa_fondos_dolares', 0, columnasDolares);
});
