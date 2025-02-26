<?php
// Incluir archivo de configuración
require_once '../../config/config.php';

// Incluir las funciones necesarias
include '../funciones/cliente_funciones.php';
include '../funciones/dolar_cronista.php';

// Calcular el promedio del dólar CCL
$promedio_ccl = ($contadoconliqui_compra + $contadoconliqui_venta) / 2;

// Obtener el saldo en pesos
$saldo_en_pesos_formateado = 10000; // Reemplaza este valor por el valor real que obtienes en tu aplicación

// Calcular el saldo en dólares
$saldo_en_dolares = $saldo_en_pesos_formateado / $promedio_ccl;

// Formatear el saldo en dólares
$saldo_en_dolares_formateado = number_format($saldo_en_dolares, 2, ',', '.');

echo "Promedio CCL: " . $promedio_ccl . "<br>";
echo "Saldo en dólares (formateado): " . $saldo_en_dolares_formateado . "<br>";
