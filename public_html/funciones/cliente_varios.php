<?php
// Obtener el id del cliente desde la URL
$cliente_id = isset($_GET['cliente_id']) ? $_GET['cliente_id'] : 1;

// Consulta para obtener los datos del cliente
$sql = "SELECT nombre, apellido FROM clientes WHERE cliente_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $cliente_id);
$stmt->execute();
$stmt->bind_result($nombre, $apellido);
$stmt->fetch();
$stmt->close();

// Obtener el saldo en efectivo del balance
$saldo_efectivo = $balance['efectivo'];
$saldo_dolares = calcular_saldo_dolares($saldo_efectivo, $contadoconliqui_compra, $contadoconliqui_venta);

// Nueva función para obtener las acciones del cliente
function obtener_acciones_cliente($conn, $cliente_id)
{
    $sql = "SELECT ticker, fecha, cantidad, precio FROM acciones WHERE cliente_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $cliente_id);
    $stmt->execute();
    $ticker = $fecha = $cantidad = $precio = null;
    $stmt->bind_result($ticker, $fecha, $cantidad, $precio);

    $acciones = [];
    while ($stmt->fetch()) {
        $acciones[] = [
            'ticker' => $ticker,
            'fecha' => $fecha,
            'cantidad' => $cantidad,
            'precio' => $precio,
        ];
    }
    $stmt->close();
    return $acciones;
}

// Obtener las acciones del cliente
$acciones = obtener_acciones_cliente($conn, $cliente_id);

$promedio_ccl = ($contadoconliqui_compra + $contadoconliqui_venta) / 2;

// Nueva función para calcular el valor inicial de las acciones en pesos
function calcular_valor_inicial_acciones($acciones)
{
    $valor_inicial_total = 0;
    foreach ($acciones as $accion) {
        $valor_inicial_total += $accion['cantidad'] * $accion['precio'];
    }
    return $valor_inicial_total;
}

// Calcular el valor inicial de las acciones en pesos
$valor_inicial_acciones_pesos = calcular_valor_inicial_acciones($acciones);

// Función para obtener el valor actual de las acciones desde Google Finance
function obtener_valor_accion($ticker)
{
    $url = "https://www.google.com/finance/quote/$ticker:BCBA?hl=es";
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
