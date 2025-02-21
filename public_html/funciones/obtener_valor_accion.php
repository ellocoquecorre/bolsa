<?php
// Configuración de error para depuración
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Verificar que el ticker esté presente en la solicitud
if (isset($_GET['ticker'])) {
    $ticker = $_GET['ticker'];

    // Función para obtener el valor de la acción desde Google Finance
    function obtener_valor_accion($ticker)
    {
        $url = "https://www.google.com/search?q={$ticker}+stock"; // URL para obtener información sobre la acción
        $contenido = file_get_contents($url);

        // Expresión regular para extraer el valor de la acción
        preg_match('/<div class="YMlKec fxKbKc">(\d+\.\d+|\d+,\d+)<\/div>/', $contenido, $coincidencias);

        // Si encontramos el valor, retornarlo, sino, retornar 0
        if (isset($coincidencias[1])) {
            return str_replace(",", ".", $coincidencias[1]);
        } else {
            return 0;
        }
    }

    // Obtener el valor de la acción usando el ticker proporcionado
    $valor_accion = obtener_valor_accion($ticker);

    // Si no encontramos un valor válido, retornamos 0
    if ($valor_accion == 0) {
        $valor_accion = 0;
    }

    // Responder con el valor de la acción
    echo json_encode(['valor_actual' => $valor_accion]);
} else {
    echo json_encode(['error' => 'No se proporcionó un ticker válido']);
}
