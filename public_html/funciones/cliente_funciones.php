<?php
// CONFIG
require_once '../../config/config.php';
require_once '../funciones/formato_dinero.php'; // <- Necesario para formatear_dinero
require_once '../funciones/dolar_cronista.php';  // <- Para obtener CCL
// FIN CONFIG

// CLIENTE_ID
if (!isset($cliente_id)) {
    $cliente_id = $_GET['cliente_id'] ?? $_POST['cliente_id'] ?? null;
}
// FIN CLIENTE_ID

// CORREDORA CLIENTE
function obtenerDatosCorredora($cliente_id)
{
    $conn = obtenerConexion();
    $sql = "SELECT url, corredora FROM clientes WHERE cliente_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$cliente_id]);
    $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
    return ['url' => $resultado['url'] ?? null, 'corredora' => $resultado['corredora'] ?? null];
}
// FIN CORREDORA CLIENTE

// CCL
$contadoconliqui_compra = obtenerCCLCompraDolarHoy();
$contadoconliqui_venta = obtenerCCLVentaDolarHoy();
$promedio_ccl = ($contadoconliqui_compra + $contadoconliqui_venta) / 2;
// FIN CCL

// SALDO EN PESOS
function obtenerSaldoPesos($cliente_id)
{
    $conn = obtenerConexion();
    $sql = "SELECT efectivo FROM balance WHERE cliente_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$cliente_id]);
    return $stmt->fetchColumn() ?? 0;
}

$saldo_en_pesos = obtenerSaldoPesos($cliente_id);
// FIN SALDO EN PESOS

// SALDO EN DÓLARES
$saldo_en_dolares = $saldo_en_pesos / $promedio_ccl;
$saldo_en_dolares_formateado = formatear_dinero($saldo_en_dolares);
// FIN SALDO EN DÓLARES

//-- ACCIONES --//
// Renderizar Acciones
function obtenerAcciones($cliente_id)
{
    $conn = obtenerConexion();

    try {
        $sql = "SELECT ticker, fecha, cantidad, precio FROM acciones WHERE cliente_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$cliente_id]);

        $acciones = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $acciones;
    } catch (PDOException $e) {
        error_log("Error en obtenerAcciones: " . $e->getMessage());
        return [];
    }
}

function formatearFechaAcciones($fecha)
{
    $date = DateTime::createFromFormat('Y-m-d', $fecha);
    return $date ? $date->format('d-m-y') : $fecha;
}
// Fin Renderizar Acciones

// Precio Actual Acciones
function obtenerPrecioActualGoogleFinance($ticker)
{
    $ticker_sanitizado = htmlspecialchars($ticker, ENT_QUOTES, 'UTF-8');
    $url = "https://www.google.com/finance/quote/{$ticker_sanitizado}:BCBA";

    $html = @file_get_contents($url);
    if (!$html) {
        error_log("No se pudo obtener el contenido de Google Finance para el ticker: $ticker");
        return 0;
    }

    // Buscar el precio entre <div class="YMlKec fxKbKc"> y </div>
    $pattern = '/<div class="YMlKec fxKbKc">([^<]+)<\/div>/';
    preg_match($pattern, $html, $matches);

    if (!empty($matches[1])) {
        $valor = str_replace(["$", ","], ["", ""], trim($matches[1]));
        return floatval($valor);
    }

    error_log("No se pudo parsear el precio actual para $ticker");
    return 0;
}
// Fin Precio Actual Acciones

// CCL Compra Acciones
function obtenerCCLCompra($cliente_id, $ticker)
{
    $conn = obtenerConexion();

    try {
        $sql = "SELECT ccl_compra FROM acciones WHERE cliente_id = ? AND ticker = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$cliente_id, $ticker]);

        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
        return $resultado ? $resultado['ccl_compra'] : 0;
    } catch (PDOException $e) {
        error_log("Error en obtenerCCLCompra: " . $e->getMessage());
        return 0;
    }
}

// Fin CCL Compra Acciones

// Historial Acciones
function obtenerHistorialAcciones($cliente_id)
{
    try {
        $conn = obtenerConexion(); // Ya devuelve un objeto PDO

        $sql = "SELECT * FROM acciones_historial WHERE cliente_id = :cliente_id";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':cliente_id', $cliente_id, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(); // Ya viene con FETCH_ASSOC por defecto desde config
    } catch (PDOException $e) {
        error_log("Error en obtenerHistorialAcciones: " . $e->getMessage());
        return [];
    }
}

// Obtener el historial de acciones del cliente
$historial_acciones = obtenerHistorialAcciones($cliente_id);
// Fin Historial Acciones


// Acciones Consolidada
function calcularValorInicialConsolidadoAcciones($acciones)
{
    $valor_inicial_consolidado = 0;

    foreach ($acciones as $accion) {
        if (isset($accion['precio'], $accion['cantidad'])) {
            $valor_inicial_consolidado += $accion['precio'] * $accion['cantidad'];
        }
    }

    return $valor_inicial_consolidado;
}
// Fin Acciones Consolidada
//-- FIN ACCIONES --//

//-- CEDEAR --//
// Renderizar Cedear
function obtenerCedear($cliente_id)
{
    $conn = obtenerConexion();

    if (!$conn) {
        error_log("Error de conexión en obtenerCedear: conexión no válida");
        return [];
    }

    $sql = "SELECT ticker_cedear, fecha_cedear, cantidad_cedear, precio_cedear FROM cedear WHERE cliente_id = ?";
    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        error_log("Error al preparar obtenerCedear: " . implode(" | ", $conn->errorInfo()));
        return [];
    }

    $stmt->execute([$cliente_id]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function formatearFechaCedear($fecha_cedear)
{
    $date = DateTime::createFromFormat('Y-m-d', $fecha_cedear);
    return $date ? $date->format('d-m-y') : $fecha_cedear;
}
// Fin Renderizar Cedear

// Precio Actual Cedear
function obtenerPrecioActualCedear($ticker_cedear)
{
    $ticker_sanitizado = htmlspecialchars($ticker_cedear, ENT_QUOTES, 'UTF-8');
    $url = "https://www.google.com/finance/quote/{$ticker_sanitizado}:BCBA";

    $html = @file_get_contents($url);
    if (!$html) {
        error_log("No se pudo obtener contenido de Google Finance para el Cedear: $ticker_cedear");
        return 0;
    }

    // Buscar el precio entre <div class="YMlKec fxKbKc"> y </div>
    $pattern = '/<div class="YMlKec fxKbKc">([^<]+)<\/div>/';
    preg_match($pattern, $html, $matches);

    if (!empty($matches[1])) {
        $valor = str_replace(["$", ","], ["", ""], trim($matches[1]));
        return floatval($valor);
    }

    error_log("No se pudo parsear el precio actual para Cedear: $ticker_cedear");
    return 0;
}
// Fin Precio Actual Cedear

// CCL Compra Cedear
function obtenerCCLCompraCedear($cliente_id, $ticker_cedear)
{
    $conn = obtenerConexion();

    if (!$conn) {
        error_log("Error de conexión en obtenerCCLCompraCedear: conexión no válida");
        return 0;
    }

    $sql = "SELECT ccl_compra_cedear FROM cedear WHERE cliente_id = ? AND ticker_cedear = ?";
    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        error_log("Error al preparar obtenerCCLCompraCedear: " . implode(" | ", $conn->errorInfo()));
        return 0;
    }

    $stmt->execute([$cliente_id, $ticker_cedear]);
    $ccl_compra = $stmt->fetchColumn();

    return $ccl_compra !== false ? floatval($ccl_compra) : 0;
}
// Fin CCL Compra Cedear

// Historial Cedear
function obtenerHistorialCedear($cliente_id)
{
    $conn = obtenerConexion();

    if (!$conn) {
        error_log("Error de conexión en obtenerHistorialCedear: conexión no válida");
        return [];
    }

    $sql = "SELECT * FROM cedear_historial WHERE cliente_id = ?";
    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        error_log("Error al preparar obtenerHistorialCedear: " . implode(" | ", $conn->errorInfo()));
        return [];
    }

    $stmt->execute([$cliente_id]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Obtener el historial de cedear del cliente
$historial_cedear = obtenerHistorialCedear($cliente_id);
// Fin Historial Cedear

// Cedear Consolidado
function calcularValorInicialConsolidadoCedear($cedear)
{
    $valor_inicial_consolidado = 0;

    foreach ($cedear as $c) {
        if (isset($c['precio_cedear'], $c['cantidad_cedear'])) {
            $valor_inicial_consolidado += $c['precio_cedear'] * $c['cantidad_cedear'];
        }
    }

    return $valor_inicial_consolidado;
}
// Fin Cedear Consolidado
//-- FIN CEDEAR --//

//-- BONOS --//
// Renderizar Bonos
function obtenerBonos($cliente_id)
{
    $conn = obtenerConexion();

    if (!$conn) {
        error_log("Error de conexión en obtenerBonos: conexión no válida");
        return [];
    }

    $sql = "SELECT ticker_bonos, fecha_bonos, cantidad_bonos, precio_bonos FROM bonos WHERE cliente_id = ?";
    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        error_log("Error al preparar obtenerBonos: " . implode(" | ", $conn->errorInfo()));
        return [];
    }

    $stmt->execute([$cliente_id]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function formatearFechaBonos($fecha_bonos)
{
    $date = DateTime::createFromFormat('Y-m-d', $fecha_bonos);
    return $date ? $date->format('d-m-y') : $fecha_bonos;
}
// Fin Renderizar Bonos

// Precio Actual Bonos
function obtenerValorActualRava($ticker_bonos)
{
    $url = "https://www.rava.com/perfil/{$ticker_bonos}";
    $html = @file_get_contents($url);

    if (!$html) {
        error_log("No se pudo obtener contenido de Rava para el bono: $ticker_bonos");
        return 0;
    }

    // Buscar contenido entre <div class="col cotizacion"> y </div>
    $pattern = '/<div class="col cotizacion">([^<]+)<\/div>/';
    preg_match($pattern, $html, $matches);

    if (!empty($matches[1])) {
        $valor = str_replace(["$", ","], ["", ""], trim($matches[1]));
        return floatval($valor);
    }

    error_log("No se pudo parsear el precio actual para bono: $ticker_bonos");
    return 0;
}
// Fin Precio Actual Bonos

// CCL Compra Bonos
function obtenerCCLCompraBonos($cliente_id, $ticker_bonos)
{
    $conn = obtenerConexion();

    try {
        $sql = "SELECT ccl_compra FROM bonos WHERE cliente_id = ? AND ticker_bonos = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$cliente_id, $ticker_bonos]);

        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
        return $resultado ? $resultado['ccl_compra'] : 0;
    } catch (PDOException $e) {
        error_log("Error en obtenerCCLCompraBonos: " . $e->getMessage());
        return 0;
    }
}
// Fin CCL Compra Bonos

// Historial Bonos
function obtenerHistorialBonos($cliente_id)
{
    $conn = obtenerConexion();

    if (!$conn) {
        error_log("Error de conexión en obtenerHistorialBonos: conexión no válida");
        return [];
    }

    $sql = "SELECT * FROM bonos_historial WHERE cliente_id = ?";
    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        error_log("Error al preparar obtenerHistorialBonos: " . implode(" | ", $conn->errorInfo()));
        return [];
    }

    $stmt->execute([$cliente_id]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

$historial_bonos = obtenerHistorialBonos($cliente_id);
// Fin Historial Bonos

// Bonos Consolidado
function calcularValorInicialConsolidadoBonos($bonos)
{
    $valor_inicial_consolidado = 0;

    foreach ($bonos as $bono) {
        if (isset($bono['precio_bonos'], $bono['cantidad_bonos'])) {
            $valor_inicial_consolidado += $bono['precio_bonos'] * $bono['cantidad_bonos'];
        }
    }

    return $valor_inicial_consolidado;
}
// Fin Bonos Consolidado
//-- FIN BONOS --//

//-- FONDOS --//
// Renderizar Fondos
function obtenerFondos($cliente_id)
{
    $conn = obtenerConexion();

    if (!$conn) {
        error_log("Error de conexión en obtenerFondos: conexión no válida");
        return [];
    }

    $sql = "SELECT ticker_fondos, fecha_fondos, cantidad_fondos, precio_fondos FROM fondos WHERE cliente_id = ?";
    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        error_log("Error al preparar obtenerFondos: " . implode(" | ", $conn->errorInfo()));
        return [];
    }

    $stmt->execute([$cliente_id]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function formatearFechaFondos($fecha_fondos)
{
    $date = DateTime::createFromFormat('Y-m-d', $fecha_fondos);
    return $date ? $date->format('d-m-y') : $fecha_fondos;
}
// Fin Renderizar Fondos

// Precio Actual Fondos
function obtenerValorActualRavaFondos($ticker_fondos)
{
    $url = "https://www.rava.com/perfil/$ticker_fondos";
    $html = @file_get_contents($url);

    if (!$html) {
        error_log("No se pudo obtener contenido de Rava para el fondo: $ticker_fondos");
        return 0;
    }

    $pattern = '/<div class="col cotizacion">([^<]+)<\/div>/';
    preg_match($pattern, $html, $matches);

    if (!empty($matches[1])) {
        $valor = str_replace(["$", ","], ["", ""], trim($matches[1]));
        return floatval($valor);
    }

    error_log("No se pudo parsear el precio actual para fondo: $ticker_fondos");
    return 0;
}
// Fin Precio Actual Fondos

// CCL Compra Fondos
function obtenerCCLCompraFondos($cliente_id, $ticker_fondos)
{
    $conn = obtenerConexion();

    if (!$conn) {
        error_log("Error de conexión en obtenerCCLCompraFondos: conexión no válida");
        return 0;
    }

    $sql = "SELECT ccl_compra_fondos FROM fondos WHERE cliente_id = ? AND ticker_fondos = ?";
    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        error_log("Error al preparar obtenerCCLCompraFondos: " . implode(" | ", $conn->errorInfo()));
        return 0;
    }

    $stmt->execute([$cliente_id, $ticker_fondos]);
    $ccl_compra = $stmt->fetchColumn();

    return $ccl_compra !== false ? floatval($ccl_compra) : 0;
}
// Fin CCL Compra Fondos

// Historial Fondos
function obtenerHistorialFondos($cliente_id)
{
    $conn = obtenerConexion();

    if (!$conn) {
        error_log("Error de conexión en obtenerHistorialFondos: conexión no válida");
        return [];
    }

    $sql = "SELECT * FROM fondos_historial WHERE cliente_id = ?";
    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        error_log("Error al preparar obtenerHistorialFondos: " . implode(" | ", $conn->errorInfo()));
        return [];
    }

    $stmt->execute([$cliente_id]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

$historial_fondos = obtenerHistorialFondos($cliente_id);
// Fin Historial Fondos

// Fondos Consolidado
function calcularValorInicialConsolidadoFondos($fondos)
{
    $valor_inicial_consolidado = 0;

    foreach ($fondos as $fondo) {
        if (isset($fondo['precio_fondos'], $fondo['cantidad_fondos'])) {
            $valor_inicial_consolidado += $fondo['precio_fondos'] * $fondo['cantidad_fondos'];
        }
    }

    return $valor_inicial_consolidado;
}
// Fin Fondos Consolidado
//-- FIN FONDOS --//

//-- TENENCIAS CONSOLIDADAS --//
function calcularValoresConsolidados($items, $tipo, $es_dolar = false, $promedio_ccl = 1, $cliente_id = null)
{
    $valor_inicial_total = 0;
    $valor_actual_total = 0;

    foreach ($items as $item) {
        switch ($tipo) {
            case 'acciones':
                $ticker = $item['ticker'];
                $precio_actual = obtenerPrecioActualGoogleFinance($ticker);
                $cantidad = $item['cantidad'];
                $precio_inicial = $item['precio'];
                $valor_compra_ccl = $es_dolar ? obtenerCCLCompra($cliente_id, $ticker) : 1;
                break;

            case 'cedear':
                $ticker = $item['ticker_cedear'];
                $precio_actual = obtenerPrecioActualCedear($ticker);
                $cantidad = $item['cantidad_cedear'];
                $precio_inicial = $item['precio_cedear'];
                $valor_compra_ccl = $es_dolar ? obtenerCCLCompraCedear($cliente_id, $ticker) : 1;
                break;

            case 'bonos':
                $ticker = $item['ticker_bonos'];
                $precio_actual = obtenerValorActualRava($ticker);
                $cantidad = $item['cantidad_bonos'];
                $precio_inicial = $item['precio_bonos'];
                $valor_compra_ccl = $es_dolar ? obtenerCCLCompraBonos($cliente_id, $ticker) : 1;
                break;

            case 'fondos':
                $ticker = $item['ticker_fondos'];
                $precio_actual = obtenerValorActualRavaFondos($ticker);
                $cantidad = $item['cantidad_fondos'];
                $precio_inicial = $item['precio_fondos'];
                $valor_compra_ccl = $es_dolar ? obtenerCCLCompraFondos($cliente_id, $ticker) : 1;
                break;

            default:
                continue 2;
        }

        if ($precio_inicial === null || $cantidad === null || $valor_compra_ccl == 0) {
            continue;
        }

        if ($es_dolar) {
            $valor_inicial = ($precio_inicial * $cantidad) / $valor_compra_ccl;
            $valor_actual = ($precio_actual * $cantidad) / $promedio_ccl;
        } else {
            $valor_inicial = $precio_inicial * $cantidad;
            $valor_actual = $precio_actual * $cantidad;
        }

        $valor_inicial_total += $valor_inicial;
        $valor_actual_total += $valor_actual;
    }

    $rendimiento = $valor_actual_total - $valor_inicial_total;
    $rentabilidad = $valor_inicial_total != 0 ? ($rendimiento / $valor_inicial_total) * 100 : 0;

    return [
        'valor_inicial' => $valor_inicial_total,
        'valor_actual' => $valor_actual_total,
        'rendimiento' => $rendimiento,
        'rentabilidad' => $rentabilidad
    ];
}

$promedio_ccl = ($contadoconliqui_compra + $contadoconliqui_venta) / 2;

// Acciones
$acciones = obtenerAcciones($cliente_id);
$consolidado_acciones_pesos = calcularValoresConsolidados($acciones, 'acciones', false);
$consolidado_acciones_dolares = calcularValoresConsolidados($acciones, 'acciones', true, $promedio_ccl, $cliente_id);

// Cedear
$cedear = obtenerCedear($cliente_id);
$consolidado_cedear_pesos = calcularValoresConsolidados($cedear, 'cedear', false);
$consolidado_cedear_dolares = calcularValoresConsolidados($cedear, 'cedear', true, $promedio_ccl, $cliente_id);

// Bonos
$bonos = obtenerBonos($cliente_id);
$consolidado_bonos_pesos = calcularValoresConsolidados($bonos, 'bonos', false);
$consolidado_bonos_dolares = calcularValoresConsolidados($bonos, 'bonos', true, $promedio_ccl, $cliente_id);

// Fondos
$fondos = obtenerFondos($cliente_id);
$consolidado_fondos_pesos = calcularValoresConsolidados($fondos, 'fondos', false);
$consolidado_fondos_dolares = calcularValoresConsolidados($fondos, 'fondos', true, $promedio_ccl, $cliente_id);

//-- FIN TENENCIAS CONSOLIDADAS --//
