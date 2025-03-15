<?php
// CONFIG
require_once '../../config/config.php';
// Incluir utilidades comunes
require_once 'formato_dinero.php';
include '../funciones/dolar_cronista.php';
// FIN CONFIG

// Renderizar Bonos
function obtenerBonos($cliente_id)
{
    global $conn;
    $sql_bonos = "SELECT ticker_bonos, fecha_bonos, cantidad_bonos, precio_bonos FROM bonos WHERE cliente_id = ?";
    $stmt_bonos = $conn->prepare($sql_bonos);
    $stmt_bonos->bind_param("i", $cliente_id);
    $stmt_bonos->execute();
    $result = $stmt_bonos->get_result();

    $bonos = [];
    while ($fila = $result->fetch_assoc()) {
        $bonos[] = $fila;
    }

    $stmt_bonos->close();
    return $bonos;
}
// Fin Renderizar Bonos

// Obtener valor actual de Rava
function obtenerValorActualRava($ticker_bonos)
{
    $url = "https://www.rava.com/perfil/$ticker_bonos";
    $html = file_get_contents($url);

    // Buscar el valor numérico después de ',&quot;ultimo&quot;:'
    $pattern = '/,&quot;ultimo&quot;:([\d.]+)/';
    preg_match($pattern, $html, $matches);

    if (isset($matches[1])) {
        $valor = $matches[1];
        // Formatear valor a número sin formato
        $valor = str_replace(".", "", $valor); // Eliminar separador de miles
        $valor = str_replace(",", ".", $valor); // Reemplazar coma decimal por punto
        $valor = (float)$valor;
        return $valor;
    } else {
        return null;
    }
}

// Ejemplo de uso
$cliente_id = 1; // ID de cliente de ejemplo
$bonos = obtenerBonos($cliente_id);
if (!empty($bonos)) {
    $ticker_bonos = $bonos[0]['ticker_bonos'];
    $valor_actual = obtenerValorActualRava($ticker_bonos);
    echo "Valor actual de $ticker_bonos: $valor_actual";
} else {
    echo "No se encontraron bonos para el cliente con ID $cliente_id.";
}
