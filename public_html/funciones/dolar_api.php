<?php
// Función para obtener los datos de la API del dólar
function obtener_datos_dolar_api()
{
    $url = 'https://dolarapi.com/v1/dolares';
    $method = 'GET';

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);

    $response = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($http_code != 200) {
        return null; // Retornar null si la llamada a la API falló
    }

    return json_decode($response, true);
}

$datos_dolar = obtener_datos_dolar_api();
if ($datos_dolar === null) {
    echo "Error: No se pudieron obtener los datos de la API.";
    exit; // Detener la ejecución si la API falla
}

// Asegúrate de que estas claves coinciden con las claves en el array devuelto por la API
$oficial_compra = isset($datos_dolar[0]['compra']) ? $datos_dolar[0]['compra'] : 'N/A';
$oficial_venta = isset($datos_dolar[0]['venta']) ? $datos_dolar[0]['venta'] : 'N/A';
$blue_compra = isset($datos_dolar[1]['compra']) ? $datos_dolar[1]['compra'] : 'N/A';
$blue_venta = isset($datos_dolar[1]['venta']) ? $datos_dolar[1]['venta'] : 'N/A';
$bolsa_compra = isset($datos_dolar[2]['compra']) ? $datos_dolar[2]['compra'] : 'N/A';
$bolsa_venta = isset($datos_dolar[2]['venta']) ? $datos_dolar[2]['venta'] : 'N/A';
$contadoconliqui_compra = isset($datos_dolar[3]['compra']) ? $datos_dolar[3]['compra'] : 'N/A';
$contadoconliqui_venta = isset($datos_dolar[3]['venta']) ? $datos_dolar[3]['venta'] : 'N/A';
$tarjeta_compra = isset($datos_dolar[6]['compra']) ? $datos_dolar[6]['compra'] : 'N/A';
$tarjeta_venta = isset($datos_dolar[6]['venta']) ? $datos_dolar[6]['venta'] : 'N/A';
$mayorista_compra = isset($datos_dolar[4]['compra']) ? $datos_dolar[4]['compra'] : 'N/A';
$mayorista_venta = isset($datos_dolar[4]['venta']) ? $datos_dolar[4]['venta'] : 'N/A';

// Función para formatear los valores numéricos como dinero
function formatear_dinero($valor)
{
    return '$' . number_format($valor, 2, ',', '.');
}

// Función para calcular el saldo en dólares
function calcular_saldo_dolares($saldo_efectivo, $ccl_compra, $ccl_venta)
{
    if ($ccl_compra == 'N/A' || $ccl_venta == 'N/A') {
        return 'N/A';
    }
    $ccl_promedio = ($ccl_compra + $ccl_venta) / 2;
    return $saldo_efectivo / $ccl_promedio;
}
