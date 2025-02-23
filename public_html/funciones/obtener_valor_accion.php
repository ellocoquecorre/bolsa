<?php
if (!isset($_GET['ticker'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Ticker no especificado']);
    exit;
}

$ticker_acciones = $_GET['ticker'];

function obtener_valor_accion($ticker_acciones)
{
    $url = "https://www.google.com/finance/quote/$ticker_acciones:BCBA?hl=es";
    $contenido = file_get_contents($url);

    if ($contenido === FALSE) {
        return null;
    }

    // Usar una expresión regular para extraer el valor de la etiqueta deseada
    preg_match('/<div class="YMlKec fxKbKc">([^<]*)<\/div>/', $contenido, $matches);

    if (isset($matches[1])) {
        // Convertir el valor a un número eliminando caracteres no deseados
        $valor = str_replace(['$', ' ', '.'], '', $matches[1]);
        $valor = str_replace(',', '.', $valor);
        return floatval($valor);
    } else {
        return null;
    }
}

$valor_actual = obtener_valor_accion($ticker_acciones);

if ($valor_actual !== null) {
    echo json_encode(['valor' => $valor_actual]);
} else {
    http_response_code(500);
    echo json_encode(['error' => 'No se pudo obtener el valor de la acción']);
}
