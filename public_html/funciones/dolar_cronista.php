<?php

// Incluir utilidades comunes si las necesitás
// require_once 'formato_dinero.php'; // Descomentá si usás esta función

// ==========================
// FUNCIONES AUXILIARES
// ==========================

function obtener_valor($url, &$compra, &$venta)
{
    $html = @file_get_contents($url);
    if ($html === FALSE) {
        $compra = "-";
        $venta = "-";
        return;
    }

    // Buscar el valor de compra
    preg_match('/<div class=text>Valor de compra<\/div><div class=val><span class="currency">\$<\/span>([\d\.,]+)/', $html, $matches);
    if (isset($matches[1])) {
        $compra = str_replace(',', '.', str_replace('.', '', $matches[1]));
    } else {
        $compra = "-";
    }

    // Buscar el valor de venta
    preg_match('/<div class=text>Valor de venta<\/div><div class=val><span class="currency">\$<\/span>([\d\.,]+)/', $html, $matches);
    if (isset($matches[1])) {
        $venta = str_replace(',', '.', str_replace('.', '', $matches[1]));
    } else {
        $venta = "-";
    }
}

function obtener_valor_dolarhoy($url, &$compra, &$venta)
{
    $html = @file_get_contents($url);
    if ($html === FALSE) {
        $compra = "-";
        $venta = "-";
        return;
    }

    // Valor de compra
    preg_match('/<div class="tile is-child"><div class="topic">Compra<\/div><div class="value">\$(\d+,\d+)/', $html, $matches);
    if (isset($matches[1])) {
        $compra = floatval(str_replace(',', '.', $matches[1]));
    } else {
        $compra = "-";
    }

    // Valor de venta
    preg_match('/<div class="tile is-child"><div class="topic">Venta<\/div><div class="value">\$(\d+,\d+)/', $html, $matches);
    if (isset($matches[1])) {
        $venta = floatval(str_replace(',', '.', $matches[1]));
    } else {
        $venta = "-";
    }
}

// ==========================
// FUNCIONES DE ACCESO PÚBLICO
// ==========================

// CCL (Contado con Liqui)
function obtenerCCLCompraDolarHoy()
{
    $url = 'https://dolarhoy.com/cotizaciondolarcontadoconliqui';
    $compra = $venta = "";
    obtener_valor_dolarhoy($url, $compra, $venta);

    return is_numeric($compra) ? floatval($compra) : 0;
}

function obtenerCCLVentaDolarHoy()
{
    $url = 'https://dolarhoy.com/cotizaciondolarcontadoconliqui';
    $compra = $venta = "";
    obtener_valor_dolarhoy($url, $compra, $venta);

    return is_numeric($venta) ? floatval($venta) : 0;
}


// Si querés agregar más funciones similares (blue, oficial, etc.), podés hacerlo aquí.
