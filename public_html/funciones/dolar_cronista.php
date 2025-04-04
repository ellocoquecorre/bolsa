<?php

// Incluir utilidades comunes
require_once 'formato_dinero.php';

// FUNCIONES COMUNES
function obtener_valor($url, &$compra, &$venta)
{
    $html = file_get_contents($url);
    if ($html === FALSE) {
        $compra = "-";
        $venta = "-";
        return;
    }

    // Buscar el valor de compra
    preg_match('/<div class=text>Valor de compra<\/div><div class=val><span class="currency">\$<\/span>([\d\.,]+)/', $html, $matches);
    if (isset($matches[1])) {
        $compra = str_replace(',', '.', str_replace('.', '', $matches[1])); // Convertir cadena a formato numérico
    } else {
        $compra = "-";
    }

    // Buscar el valor de venta
    preg_match('/<div class=text>Valor de venta<\/div><div class=val><span class="currency">\$<\/span>([\d\.,]+)/', $html, $matches);
    if (isset($matches[1])) {
        $venta = str_replace(',', '.', str_replace('.', '', $matches[1])); // Convertir cadena a formato numérico
    } else {
        $venta = "-";
    }
}

// DOLAR BNA
$oficial_compra = $oficial_venta = "";
obtener_valor('https://www.cronista.com/MercadosOnline/moneda.html?id=ARS', $oficial_compra, $oficial_venta);

// DOLAR BLUE
$blue_compra = $blue_venta = "";
obtener_valor('https://www.cronista.com/MercadosOnline/moneda.html?id=ARSB', $blue_compra, $blue_venta);

// DOLAR MEP
$bolsa_compra = $bolsa_venta = "";
obtener_valor('https://www.cronista.com/MercadosOnline/moneda.html?id=ARSMEP', $bolsa_compra, $bolsa_venta);

// DOLAR TARJETA
$tarjeta_compra = $tarjeta_venta = "";
obtener_valor('https://www.cronista.com/MercadosOnline/moneda.html?id=ARSTAR', $tarjeta_compra, $tarjeta_venta);

// DOLAR MAYORISTA
$mayorista_compra = $mayorista_venta = "";
obtener_valor('https://www.cronista.com/MercadosOnline/moneda.html?id=ARSVHM', $mayorista_compra, $mayorista_venta);

// DOLAR CCL
function obtener_valor_dolarhoy($url, &$compra, &$venta)
{
    $html = file_get_contents($url);
    if ($html === FALSE) {
        $compra = "-";
        $venta = "-";
        return;
    }

    // Buscar el valor de compra
    preg_match('/<div class="tile is-child"><div class="topic">Compra<\/div><div class="value">\$(\d+,\d+)/', $html, $matches);
    if (isset($matches[1])) {
        $compra = floatval(str_replace(',', '.', $matches[1])); // Convertir cadena a formato numérico
    } else {
        $compra = "-";
    }

    // Buscar el valor de venta
    preg_match('/<div class="tile is-child"><div class="topic">Venta<\/div><div class="value">\$(\d+,\d+)/', $html, $matches);
    if (isset($matches[1])) {
        $venta = floatval(str_replace(',', '.', $matches[1])); // Convertir cadena a formato numérico
    } else {
        $venta = "-";
    }
}

// URL de prueba
$url = 'https://dolarhoy.com/cotizaciondolarcontadoconliqui';
$contadoconliqui_compra = $contadoconliqui_venta = "";
obtener_valor_dolarhoy($url, $contadoconliqui_compra, $contadoconliqui_venta);
