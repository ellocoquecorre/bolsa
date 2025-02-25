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

// CEDEAR DEL CLIENTE
function obtener_cedear_cliente($conn, $cliente_id)
{
    $sql = "SELECT ticker, fecha, cantidad, precio FROM cedear WHERE cliente_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $cliente_id);
    $stmt->execute();
    $ticker_cedear = $fecha_cedear = $cantidad_cedear = $precio_cedear = null;
    $stmt->bind_result($ticker_cedear, $fecha_cedear, $cantidad_cedear, $precio_cedear);

    $cedear = [];
    while ($stmt->fetch()) {
        $cedear[] = [
            'ticker' => $ticker_cedear,
            'fecha' => $fecha_cedear,
            'cantidad' => $cantidad_cedear,
            'precio' => $precio_cedear,
        ];
    }
    $stmt->close();
    return $cedear;
}

// Obtener los cedears del cliente
$cedear = obtener_cedear_cliente($conn, $cliente_id);

$promedio_ccl = ($contadoconliqui_compra + $contadoconliqui_venta) / 2;

// Nueva función para calcular el valor inicial del cedear en pesos
function calcular_valor_inicial_cedear($cedear)
{
    $valor_inicial_total_cedear = 0;
    foreach ($cedear as $accion) {
        $valor_inicial_total_cedear += $accion['cantidad'] * $accion['precio'];
    }
    return $valor_inicial_total_cedear;
}

// Calcular el valor inicial de los cedear en pesos
$valor_inicial_cedear_pesos = calcular_valor_inicial_cedear($cedear);

function obtener_valor_cedear($ticker_cedear)
{
    $url = "https://www.google.com/finance/quote/{$ticker_cedear}:BCBA?hl=es";

    // Realizar la solicitud HTTP a la URL
    $html = file_get_contents($url);

    if ($html === false) {
        // Si no pudimos obtener el contenido de la página, devolver null
        return null;
    }

    // Buscar el valor dentro de la página HTML
    $pattern = '/<div class=\"YMlKec fxKbKc\">([^<]+)<\/div>/';
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

// Inicializamos una variable para acumular el total de los cedear en pesos
$total_valor_cedear_pesos = 0;

// Iteramos sobre los cedear del cliente
foreach ($cedear as $accion) {
    // Formateamos la cantidad de cedear (si es nula, lo ponemos como 0)
    $cantidad_cedear_compra_cedear_formateada = is_null($accion['cantidad']) ? 0 : $accion['cantidad'];

    // Obtenemos el valor actual del cedear (esto debe ser un valor numérico)
    $valor_actual_cedear = obtener_valor_cedear($accion['ticker']); // Obtenemos el valor sin multiplicar por promedio_ccl aún

    // Si el valor obtenido es 0 o nulo, lo manejamos adecuadamente
    if ($valor_actual_cedear === null || $valor_actual_cedear == 0) {
        $valor_actual_cedear = 0; // Asignamos 0 si no se obtiene un valor válido
    }

    // Multiplicamos el valor actual del cedear por la cantidad de cedear
    $valor_total_cedear_pesos = $cantidad_cedear_compra_cedear_formateada * $valor_actual_cedear;

    // Sumamos el valor total de este cedear al total acumulado
    $total_valor_cedear_pesos += $valor_total_cedear_pesos;
}

// Ahora podemos renderizar el valor total en la celda correspondiente
$valor_total_cedear_pesos_formateado = number_format($total_valor_cedear_pesos, 2, ',', '.');

// Calculamos el rendimiento restando el valor actual menos el valor inicial
$rendimiento_cedear_pesos = $total_valor_cedear_pesos - $valor_inicial_cedear_pesos;

// Definimos el color según el rendimiento
$color_rendimiento_cedear = ($rendimiento_cedear_pesos >= 0) ? 'green' : 'red';

// Formateamos el rendimiento para mostrarlo en la tabla
$rendimiento_cedear_pesos_formateado = number_format($rendimiento_cedear_pesos, 2, ',', '.');

// Cálculo de rentabilidad con la nueva fórmula
$rentabilidad_cedear_pesos_nueva = ($total_valor_cedear_pesos != 0) ? (($total_valor_cedear_pesos - $valor_inicial_cedear_pesos) / $total_valor_cedear_pesos) * 100 : 0;

// Formateamos la rentabilidad
$rentabilidad_cedear_pesos_nueva_formateada = number_format($rentabilidad_cedear_pesos_nueva, 2, ',', '.');

// Definimos el color de la rentabilidad: verde si es positiva o 0, rojo si es negativa
$color_rentabilidad_cedear_nueva = ($rentabilidad_cedear_pesos_nueva >= 0) ? 'green' : 'red';

// Inicializamos el total de los cedear
$total_valor_cedear_dolares = 0;

// Recorremos todos los cedear para calcular el total
foreach ($cedear as $accion) {
    // Obtener la cantidad y el valor de compra de los cedear
    $cantidad_cedear_compra_cedear = is_null($accion['cantidad']) ? 0 : $accion['cantidad'];

    // Obtenemos el valor actual del cedear desde Google Finance (o alguna fuente similar)
    $valor_actual = obtener_valor_cedear($accion['ticker']);

    if ($valor_actual === null) {
        // Si no se pudo obtener el valor, usamos un valor predeterminado (0 o N/A)
        $valor_actual = 0;
    }

    // Calculamos el valor actual de los cedear en dólares usando el valor actual
    $valor_actual_dolares_cedear = $valor_actual / $promedio_ccl;

    // Acumulamos el valor total de los cedear
    $total_valor_cedear_dolares += $cantidad_cedear_compra_cedear * $valor_actual_dolares_cedear;
}

// Formateamos el total en dólares con el separador correcto
$total_valor_cedear_dolares_formateado = number_format($total_valor_cedear_dolares, 2, ',', '.');

function obtener_datos_cedear($accion)
{
    // Formatear cantidad
    $cantidad_cedear_compra_cedear_formateada = is_null($accion['cantidad']) ? '0' : number_format($accion['cantidad'], 0, '', '.');

    // Formatear valor compra
    $valor_compra_cedear_pesos_formateado = is_null($accion['precio']) ? '0,00' : number_format($accion['precio'], 2, ',', '.');

    // Formatear fecha de compra
    $fecha_cedear_compra_cedear_formateada = is_null($accion['fecha']) ? 'N/A' : date("d-m-Y", strtotime($accion['fecha']));

    // Obtener el valor actual del cedear
    $valor_actual = obtener_valor_cedear($accion['ticker']);
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
        'cantidad_compra' => $cantidad_cedear_compra_cedear_formateada,
        'valor_compra' => $valor_compra_cedear_pesos_formateado,
        'fecha_compra' => $fecha_cedear_compra_cedear_formateada,
        'valor_actual' => $valor_actual_pesos_formateado,
        'rendimiento' => $rendimiento_formateado,
        'color_rendimiento' => $color_rendimiento,
        'rentabilidad' => $rentabilidad_formateada,
        'color_rentabilidad' => $color_rentabilidad
    ];
}

function calcularValoresCedear($valor_inicial_cedear_pesos, $promedio_ccl, $total_valor_cedear_dolares_formateado)
{
    // Convertimos los valores a números sin formato
    $valor_inicial_dolares_cedear = $valor_inicial_cedear_pesos / $promedio_ccl;
    $valor_actual_dolares_cedear = floatval(str_replace(',', '.', str_replace('.', '', $total_valor_cedear_dolares_formateado)));

    // Calculamos el rendimiento
    $rendimiento_cedear = $valor_actual_dolares_cedear - $valor_inicial_dolares_cedear;

    // Definir color según el resultado
    $color_rendimiento_cedear = ($rendimiento_cedear >= 0) ? 'green' : 'red';

    // Calculamos la rentabilidad en porcentaje
    $rentabilidad_cedear = ($valor_inicial_dolares_cedear != 0) ? (($rendimiento_cedear / $valor_inicial_dolares_cedear) * 100) : 0;

    // Definir color de rentabilidad
    $color_rentabilidad_cedear = ($rentabilidad_cedear >= 0) ? 'green' : 'red';

    // Formateamos los valores antes de mostrarlos
    $valor_inicial_formateado_cedear = number_format($valor_inicial_dolares_cedear, 2, ',', '.');
    $rendimiento_formateado_cedear = number_format($rendimiento_cedear, 2, ',', '.');
    $rentabilidad_formateada_cedear = number_format($rentabilidad_cedear, 2, ',', '.') . ' %';

    // Retornar los valores calculados
    return [
        'valor_inicial_formateado' => $valor_inicial_formateado_cedear,
        'valor_actual_formateado' => $total_valor_cedear_dolares_formateado,
        'rendimiento_formateado' => $rendimiento_formateado_cedear,
        'rentabilidad_formateada' => $rentabilidad_formateada_cedear,
        'color_rendimiento' => $color_rendimiento_cedear,
        'color_rentabilidad' => $color_rentabilidad_cedear
    ];
}

function obtener_cedear_dolares($cedear, $promedio_ccl)
{
    $cedear_formateadas = [];

    foreach ($cedear as $accion) {
        $cantidad_cedear_compra_cedear_formateada = is_null($accion['cantidad']) ? '0' : number_format($accion['cantidad'], 0, '', '.');

        // Obtenemos el valor actual del cedear desde Google Finance
        $valor_actual = obtener_valor_cedear($accion['ticker']); // Llamamos a la función para obtener el valor actualizado

        if ($valor_actual === null) {
            // Si no se pudo obtener el valor, mostrar un mensaje por defecto o manejar el error
            $valor_actual = 0; // O alguna otra acción como mostrar 'N/A' o 'Error'
        }

        // Cálculos sin formatear los números
        $valor_actual_dolares_cedear = $valor_actual / $promedio_ccl; // Aplicamos la fórmula sin redondeos
        $valor_compra_dolares_cedear = $accion['precio'] / $promedio_ccl; // Cálculo de valor compra en dólares

        // Formateamos los valores solo al momento de mostrar
        $valor_compra_dolares_cedear_formateado = number_format($valor_compra_dolares_cedear, 2, ',', '.');
        $valor_actual_dolares_cedear_formateado = number_format($valor_actual_dolares_cedear, 2, ',', '.'); // Lo formateamos como corresponde
        $fecha_cedear_compra_cedear_formateada = is_null($accion['fecha']) ? 'N/A' : date("d-m-Y", strtotime($accion['fecha']));

        // Calcular rentabilidad en porcentaje
        $rentabilidad_cedear = (($valor_actual_dolares_cedear - $valor_compra_dolares_cedear) / $valor_compra_dolares_cedear) * 100;

        // Formateamos el valor de la rentabilidad con 2 decimales y el símbolo de porcentaje
        $rentabilidad_formateada_cedear = number_format($rentabilidad_cedear, 2, ',', '.') . ' %';

        // Determinar el color: verde si la rentabilidad es mayor o igual a 0, rojo si es negativa
        $color_rentabilidad_cedear = ($rentabilidad_cedear >= 0) ? 'green' : 'red';

        // Cálculo del rendimiento
        $rendimiento_cedear = ($valor_actual_dolares_cedear * $accion['cantidad']) - ($valor_compra_dolares_cedear * $accion['cantidad']);

        // Formatear el resultado del rendimiento
        $rendimiento_formateado_cedear = number_format($rendimiento_cedear, 2, ',', '.');

        // Determinar el color del rendimiento
        $color_rendimiento_cedear = ($rendimiento_cedear >= 0) ? 'green' : 'red';

        // Guardamos todo en el array de resultados formateados
        $cedear_formateadas[] = [
            'ticker' => $accion['ticker'],
            'fecha' => $fecha_cedear_compra_cedear_formateada,
            'cantidad' => $cantidad_cedear_compra_cedear_formateada,
            'valor_compra' => "u\$s {$valor_compra_dolares_cedear_formateado}",
            'valor_actual' => "u\$s {$valor_actual_dolares_cedear_formateado}",
            'rendimiento' => "<span style='color: {$color_rendimiento_cedear};'>u\$s {$rendimiento_formateado_cedear}</span>",
            'rentabilidad' => "<span style='color: {$color_rentabilidad_cedear};'>{$rentabilidad_formateada_cedear}</span>"
        ];
    }

    return $cedear_formateadas;
}
