<?php
// Precio Actual Cedear
function obtenerPrecioActualCedear($ticker_cedear)
{
    $url = "https://finance.yahoo.com/quote/$ticker.BA/";
    $html = file_get_contents($url);

    // Crear un nuevo DOMDocument
    $dom = new DOMDocument();
    @$dom->loadHTML($html);

    // Buscar el valor numérico en la etiqueta que contiene data-testid="qsp-price"
    $finder = new DomXPath($dom);
    $nodes = $finder->query("//*[contains(@data-testid, 'qsp-price')]");

    if ($nodes->length > 0) {
        $valor = $nodes->item(0)->nodeValue;
        // Formatear valor a número sin formato
        $valor = str_replace(",", "", $valor); // Eliminar comas de miles
        $valor = (float)str_replace(".", "", substr($valor, 0, -3)) . "." . substr($valor, -2); // Reemplazar punto decimal y reconstruir
        $valor = number_format($valor, 2, ',', '.'); // Formatear con punto como separador de miles y coma como separador de decimales
        return $valor;
    } else {
        return null;
    }
}
// Fin Precio Actual Cedear

// Test de la función
$ticker = "AAPL"; // Reemplaza con el ticker del CEDEAR que deseas probar
$precio = obtenerPrecioActualCedear($ticker);
if ($precio !== null) {
    echo "El precio actual de $ticker es $precio.\n";
} else {
    echo "No se pudo obtener el precio para $ticker.\n";
}
?>