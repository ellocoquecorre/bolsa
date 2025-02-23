<?php

// DATOS DEL CLIENTE
$sql = "SELECT nombre, apellido FROM clientes WHERE cliente_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $cliente_id);
$stmt->execute();
$stmt->bind_result($nombre, $apellido);
$stmt->fetch();
$stmt->close();

// BALANCE DEL CLIENTE
$saldo_efectivo = $balance['efectivo'];
$saldo_dolares = calcular_saldo_dolares($saldo_efectivo, $contadoconliqui_compra, $contadoconliqui_venta);

// ACCIONES DEL CLIENTE
function obtener_acciones_cliente($conn, $cliente_id)
{
    $sql = "SELECT ticker, fecha, cantidad, precio FROM acciones WHERE cliente_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $cliente_id);
    $stmt->execute();
    $ticker_acciones = $fecha_acciones = $cantidad_acciones = $precio_acciones = null;
    $stmt->bind_result($ticker_acciones, $fecha_acciones, $cantidad_acciones, $precio_acciones);

    $acciones = [];
    while ($stmt->fetch()) {
        $acciones[] = [
            'ticker' => $ticker_acciones,
            'fecha' => $fecha_acciones,
            'cantidad' => $cantidad_acciones,
            'precio' => $precio_acciones,
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

function obtener_valor_accion($ticker_acciones)
{
    $url = "https://www.google.com/finance/quote/{$ticker_acciones}:BCBA?hl=es";

    // Realizar la solicitud HTTP a la URL
    $html = file_get_contents($url);

    if ($html === false) {
        // Si no pudimos obtener el contenido de la página, devolver null
        return null;
    }

    // Buscar el valor dentro de la página HTML
    $pattern = '/<div class="YMlKec fxKbKc">([^<]+)<\/div>/';
    preg_match($pattern, $html, $matches);

    // Verificamos si encontramos el valor
    if (isset($matches[1])) {
        // Limpiar el valor numérico, quitando cualquier texto no necesario
        $valor = $matches[1];

        // Eliminar cualquier símbolo de moneda, como "$"
        $valor = preg_replace('/[^0-9,.-]/', '', $valor);

        // Cambiar la coma por un punto para el separador de miles
        // y reemplazar el punto por coma para decimales
        $valor = str_replace('.', '', $valor); // Eliminar puntos de miles
        $valor = str_replace(',', '.', $valor); // Convertir la coma a punto decimal

        // Convertir el valor a formato numérico
        $valor = (float)$valor;

        // Devolver el valor numérico limpio
        return $valor;
    }

    // Si no encontramos el valor, devolver null
    return null;
}
// Inicializamos una variable para acumular el total de las acciones en pesos
$total_valor_acciones_pesos = 0;

// Iteramos sobre las acciones del cliente
foreach ($acciones as $accion) {
    // Formateamos la cantidad de acciones (si es nula, lo ponemos como 0)
    $cantidad_acciones_compra_acciones_formateada = is_null($accion['cantidad']) ? 0 : $accion['cantidad'];

    // Obtenemos el valor actual de la acción (esto debe ser un valor numérico)
    $valor_actual_accion = obtener_valor_accion($accion['ticker']); // Obtenemos el valor sin multiplicar por promedio_ccl aún

    // Si el valor obtenido es 0 o nulo, lo manejamos adecuadamente
    if ($valor_actual_accion === null || $valor_actual_accion == 0) {
        $valor_actual_accion = 0; // Asignamos 0 si no se obtiene un valor válido
    }

    // Multiplicamos el valor actual de la acción por la cantidad de acciones
    $valor_total_acciones_pesos = $cantidad_acciones_compra_acciones_formateada * $valor_actual_accion;

    // Sumamos el valor total de esta acción al total acumulado
    $total_valor_acciones_pesos += $valor_total_acciones_pesos;
}

// Ahora podemos renderizar el valor total en la celda correspondiente
$valor_total_acciones_pesos_formateado = number_format($total_valor_acciones_pesos, 2, ',', '.');

// Calculamos el rendimiento restando el valor actual menos el valor inicial
$rendimiento_acciones_pesos = $total_valor_acciones_pesos - $valor_inicial_acciones_pesos;

// Definimos el color según el rendimiento
$color_rendimiento = ($rendimiento_acciones_pesos >= 0) ? 'green' : 'red';

// Formateamos el rendimiento para mostrarlo en la tabla
$rendimiento_acciones_pesos_formateado = number_format($rendimiento_acciones_pesos, 2, ',', '.');

// Cálculo de rentabilidad con la nueva fórmula
$rentabilidad_acciones_pesos_nueva = (($total_valor_acciones_pesos - $valor_inicial_acciones_pesos) / $total_valor_acciones_pesos) * 100;

// Formateamos la rentabilidad
$rentabilidad_acciones_pesos_nueva_formateada = number_format($rentabilidad_acciones_pesos_nueva, 2, ',', '.');

// Definimos el color de la rentabilidad: verde si es positiva o 0, rojo si es negativa
$color_rentabilidad_nueva = ($rentabilidad_acciones_pesos_nueva >= 0) ? 'green' : 'red';

// Inicializamos el total de las acciones
$total_valor_acciones_dolares = 0;

// Recorremos todas las acciones para calcular el total
foreach ($acciones as $accion) {
    // Obtener la cantidad y el valor de compra de las acciones
    $cantidad_acciones_compra_acciones = is_null($accion['cantidad']) ? 0 : $accion['cantidad'];

    // Obtenemos el valor actual de la acción desde Google Finance (o alguna fuente similar)
    $valor_actual = obtener_valor_accion($accion['ticker']);

    if ($valor_actual === null) {
        // Si no se pudo obtener el valor, usamos un valor predeterminado (0 o N/A)
        $valor_actual = 0;
    }

    // Calculamos el valor actual de las acciones en dólares usando el valor actual
    $valor_actual_dolares = $valor_actual / $promedio_ccl;

    // Acumulamos el valor total de las acciones
    $total_valor_acciones_dolares += $cantidad_acciones_compra_acciones * $valor_actual_dolares;
}

// Formateamos el total en dólares con el separador correcto
$total_valor_acciones_dolares_formateado = number_format($total_valor_acciones_dolares, 2, ',', '.');

function obtener_datos_accion($accion)
{
    // Formatear cantidad
    $cantidad_acciones_compra_acciones_formateada = is_null($accion['cantidad']) ? '0' : number_format($accion['cantidad'], 0, '', '.');

    // Formatear valor compra
    $valor_compra_acciones_pesos_formateado = is_null($accion['precio']) ? '0,00' : number_format($accion['precio'], 2, ',', '.');

    // Formatear fecha de compra
    $fecha_acciones_compra_acciones_formateada = is_null($accion['fecha']) ? 'N/A' : date("d-m-Y", strtotime($accion['fecha']));

    // Obtener el valor actual de la acción
    $valor_actual = obtener_valor_accion($accion['ticker']);
    if ($valor_actual !== null) {
        // Formatear el valor actual
        $valor_actual_pesos_formateado = number_format($valor_actual, 2, ',', '.');

        // Calcular el rendimiento
        $valor_actual_pesos = $valor_actual;
        $valor_compra_pesos = $accion['precio'];
        $rendimiento = ($accion['cantidad'] * $valor_actual_pesos) - ($accion['cantidad'] * $valor_compra_pesos);
        $rendimiento_formateado = number_format($rendimiento, 2, ',', '.');
        $color_rendimiento = ($rendimiento >= 0) ? 'green' : 'red';

        // Calcular la rentabilidad
        $rentabilidad = (($valor_actual_pesos - $valor_compra_pesos) / $valor_compra_pesos) * 100;
        $rentabilidad_formateada = number_format($rentabilidad, 2, ',', '.') . ' %';
        $color_rentabilidad = ($rentabilidad >= 0) ? 'green' : 'red';
    } else {
        // Si no se puede obtener el valor actual, mostrar 'N/A'
        $valor_actual_pesos_formateado = 'N/A';
        $rendimiento_formateado = 'N/A';
        $color_rendimiento = 'black';
        $rentabilidad_formateada = 'N/A';
        $color_rentabilidad = 'black';
    }

    // Devolver todos los datos formateados
    return [
        'cantidad_compra' => $cantidad_acciones_compra_acciones_formateada,
        'valor_compra' => $valor_compra_acciones_pesos_formateado,
        'fecha_compra' => $fecha_acciones_compra_acciones_formateada,
        'valor_actual' => $valor_actual_pesos_formateado,
        'rendimiento' => $rendimiento_formateado,
        'color_rendimiento' => $color_rendimiento,
        'rentabilidad' => $rentabilidad_formateada,
        'color_rentabilidad' => $color_rentabilidad
    ];
}

function calcularValoresAcciones($valor_inicial_acciones_pesos, $promedio_ccl, $total_valor_acciones_dolares_formateado)
{
    // Convertimos los valores a números sin formato
    $valor_inicial_dolares = $valor_inicial_acciones_pesos / $promedio_ccl;
    $valor_actual_dolares = floatval(str_replace(',', '.', str_replace('.', '', $total_valor_acciones_dolares_formateado)));

    // Calculamos el rendimiento
    $rendimiento = $valor_actual_dolares - $valor_inicial_dolares;

    // Definir color según el resultado
    $color_rendimiento = ($rendimiento >= 0) ? 'green' : 'red';

    // Calculamos la rentabilidad en porcentaje
    $rentabilidad = ($valor_inicial_dolares != 0) ? (($rendimiento / $valor_inicial_dolares) * 100) : 0;

    // Definir color de rentabilidad
    $color_rentabilidad = ($rentabilidad >= 0) ? 'green' : 'red';

    // Formateamos los valores antes de mostrarlos
    $valor_inicial_formateado = number_format($valor_inicial_dolares, 2, ',', '.');
    $rendimiento_formateado = number_format($rendimiento, 2, ',', '.');
    $rentabilidad_formateada = number_format($rentabilidad, 2, ',', '.') . ' %';

    // Retornar los valores calculados
    return [
        'valor_inicial_formateado' => $valor_inicial_formateado,
        'valor_actual_formateado' => $total_valor_acciones_dolares_formateado,
        'rendimiento_formateado' => $rendimiento_formateado,
        'rentabilidad_formateada' => $rentabilidad_formateada,
        'color_rendimiento' => $color_rendimiento,
        'color_rentabilidad' => $color_rentabilidad
    ];
}

// SOiNK
function obtener_acciones_dolares($acciones, $promedio_ccl)
{
    $acciones_formateadas = [];

    foreach ($acciones as $accion) {
        $cantidad_acciones_compra_acciones_formateada = is_null($accion['cantidad']) ? '0' : number_format($accion['cantidad'], 0, '', '.');

        // Obtenemos el valor actual de la acción desde Google Finance
        $valor_actual = obtener_valor_accion($accion['ticker']); // Llamamos a la función para obtener el valor actualizado

        if ($valor_actual === null) {
            // Si no se pudo obtener el valor, mostrar un mensaje por defecto o manejar el error
            $valor_actual = 0; // O alguna otra acción como mostrar 'N/A' o 'Error'
        }

        // Cálculos sin formatear los números
        $valor_actual_dolares = $valor_actual / $promedio_ccl; // Aplicamos la fórmula sin redondeos
        $valor_compra_dolares = $accion['precio'] / $promedio_ccl; // Cálculo de valor compra en dólares

        // Formateamos los valores solo al momento de mostrar
        $valor_compra_dolares_formateado = number_format($valor_compra_dolares, 2, ',', '.');
        $valor_actual_dolares_formateado = number_format($valor_actual_dolares, 2, ',', '.'); // Lo formateamos como corresponde
        $fecha_acciones_compra_acciones_formateada = is_null($accion['fecha']) ? 'N/A' : date("d-m-Y", strtotime($accion['fecha']));

        // Calcular rentabilidad en porcentaje
        $rentabilidad = (($valor_actual_dolares - $valor_compra_dolares) / $valor_compra_dolares) * 100;

        // Formateamos el valor de la rentabilidad con 2 decimales y el símbolo de porcentaje
        $rentabilidad_formateada = number_format($rentabilidad, 2, ',', '.') . ' %';

        // Determinar el color: verde si la rentabilidad es mayor o igual a 0, rojo si es negativa
        $color_rentabilidad = ($rentabilidad >= 0) ? 'green' : 'red';

        // Cálculo del rendimiento
        $rendimiento = ($valor_actual_dolares * $accion['cantidad']) - ($valor_compra_dolares * $accion['cantidad']);

        // Formatear el resultado del rendimiento
        $rendimiento_formateado = number_format($rendimiento, 2, ',', '.');

        // Determinar el color del rendimiento
        $color_rendimiento = ($rendimiento >= 0) ? 'green' : 'red';

        // Guardamos todo en el array de resultados formateados
        $acciones_formateadas[] = [
            'ticker' => $accion['ticker'],
            'fecha' => $fecha_acciones_compra_acciones_formateada,
            'cantidad' => $cantidad_acciones_compra_acciones_formateada,
            'valor_compra' => "u\$s {$valor_compra_dolares_formateado}",
            'valor_actual' => "u\$s {$valor_actual_dolares_formateado}",
            'rendimiento' => "<span style='color: {$color_rendimiento};'>u\$s {$rendimiento_formateado}</span>",
            'rentabilidad' => "<span style='color: {$color_rentabilidad};'>{$rentabilidad_formateada}</span>"
        ];
    }

    return $acciones_formateadas;
}
