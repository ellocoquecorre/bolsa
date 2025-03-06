<?php
// Definir la función formatear_dinero si no está definida
if (!function_exists('formatear_dinero')) {
    function formatear_dinero($numero)
    {
        return number_format($numero, 2, ',', '.');
    }
}

if (!function_exists('formatear_numero')) {
    function formatear_numero($numero)
    {
        return number_format($numero, 0, ',', '.');
    }
}

// Definir una nueva función para formatear y colorear valores específicos
if (!function_exists('formatear_y_colorear_valor')) {
    function formatear_y_colorear_valor($numero, $moneda = '$')
    {
        $color = $numero >= 0 ? 'green' : 'red';
        return '<span style="color:' . $color . ';">' . $moneda . ' ' . formatear_dinero($numero) . '</span>';
    }
}

if (!function_exists('formatear_y_colorear_porcentaje')) {
    function formatear_y_colorear_porcentaje($numero)
    {
        $color = $numero >= 0 ? 'green' : 'red';
        return '<span style="color:' . $color . ';">' . formatear_dinero($numero) . ' %</span>';
    }
}
