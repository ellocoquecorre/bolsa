<?php
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
        $compra = $matches[1];
    } else {
        $compra = "-";
    }

    // Buscar el valor de venta
    preg_match('/<div class=text>Valor de venta<\/div><div class=val><span class="currency">\$<\/span>([\d\.,]+)/', $html, $matches);
    if (isset($matches[1])) {
        $venta = $matches[1];
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

// DOLAR CCL
$contadoconliqui_compra = $contadoconliqui_venta = "";
obtener_valor('https://www.cronista.com/MercadosOnline/moneda.html?id=ARSCONT', $contadoconliqui_compra, $contadoconliqui_venta);

// DOLAR TARJETA
$tarjeta_compra = $tarjeta_venta = "";
obtener_valor('https://www.cronista.com/MercadosOnline/moneda.html?id=ARSTAR', $tarjeta_compra, $tarjeta_venta);

// DOLAR MAYORISTA
$mayorista_compra = $mayorista_venta = "";
obtener_valor('https://www.cronista.com/MercadosOnline/moneda.html?id=ARSVHM', $mayorista_compra, $mayorista_venta);

// Mostrar resultados
echo "Dolar BNA: Compra = $oficial_compra, Venta = $oficial_venta\n";
echo "Dolar Blue: Compra = $blue_compra, Venta = $blue_venta\n";
echo "Dolar MEP: Compra = $bolsa_compra, Venta = $bolsa_venta\n";
echo "Dolar CCL: Compra = $contadoconliqui_compra, Venta = $contadoconliqui_venta\n";
echo "Dolar Tarjeta: Compra = $tarjeta_compra, Venta = $tarjeta_venta\n";
echo "Dolar Mayorista: Compra = $mayorista_compra, Venta = $mayorista_venta\n";
