<?php
// Definir la función formatear_dinero si no está definida
if (!function_exists('formatear_dinero')) {
    function formatear_dinero($numero)
    {
        return number_format($numero, 2, ',', '.');
    }
}
