<?php
// CONFIG
require_once '../../config/config.php';
require_once 'dolar_cronista.php'; // importante para contadoconliqui_compra y venta
// FIN CONFIG

// Hacer accesible cliente_id si está disponible globalmente
$cliente_id = $GLOBALS['cliente_id'] ?? null;

// CLIENTE_ID
function obtenerClienteId($email)
{
    global $conexion;

    try {
        $stmt = $conexion->prepare("SELECT cliente_id FROM clientes WHERE email = :email");
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->execute();

        $fila = $stmt->fetch();
        return $fila ? (int)$fila['cliente_id'] : null;
    } catch (PDOException $e) {
        error_log('Error en obtenerClienteId: ' . $e->getMessage());
        return null;
    }
}
// FIN CLIENTE_ID

// DATOS DEL CLIENTE
function obtenerDatosCliente($cliente_id)
{
    global $conexion;

    try {
        $stmt = $conexion->prepare("SELECT nombre, apellido, telefono, corredora, url FROM clientes WHERE cliente_id = :cliente_id");
        $stmt->bindParam(':cliente_id', $cliente_id, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetch() ?: [];
    } catch (PDOException $e) {
        error_log('Error en obtenerDatosCliente: ' . $e->getMessage());
        return [];
    }
}
// FIN DATOS DEL CLIENTE

// CORREDORA CLIENTE
function obtenerCorredoraCliente($cliente_id)
{
    global $conexion;

    try {
        $stmt = $conexion->prepare("SELECT corredora FROM clientes WHERE cliente_id = :cliente_id");
        $stmt->bindParam(':cliente_id', $cliente_id, PDO::PARAM_INT);
        $stmt->execute();

        $fila = $stmt->fetch();
        return $fila ? $fila['corredora'] : '';
    } catch (PDOException $e) {
        error_log('Error en obtenerCorredoraCliente: ' . $e->getMessage());
        return '';
    }
}
// FIN CORREDORA CLIENTE

// DATOS COMPLETOS DE LA CORREDORA
function obtenerDatosCorredora($cliente_id)
{
    global $conexion;

    try {
        $stmt = $conexion->prepare("SELECT corredora, url FROM clientes WHERE cliente_id = :cliente_id");
        $stmt->bindParam(':cliente_id', $cliente_id, PDO::PARAM_INT);
        $stmt->execute();

        $fila = $stmt->fetch(PDO::FETCH_ASSOC);

        return $fila ? [
            'corredora' => $fila['corredora'] ?? '',
            'url_corredora' => $fila['url'] ?? ''
        ] : ['corredora' => '', 'url_corredora' => ''];
    } catch (PDOException $e) {
        error_log('Error en obtenerDatosCorredora: ' . $e->getMessage());
        return ['corredora' => '', 'url_corredora' => ''];
    }
}

// FIN DATOS COMPLETOS DE LA CORREDORA

// SALDO EN PESOS
function obtenerSaldoPesos($cliente_id)
{
    global $conexion;

    try {
        $stmt = $conexion->prepare("SELECT efectivo FROM balance WHERE cliente_id = :cliente_id");
        $stmt->bindParam(':cliente_id', $cliente_id, PDO::PARAM_INT);
        $stmt->execute();

        $fila = $stmt->fetch();
        return $fila ? (float)$fila['efectivo'] : 0;
    } catch (PDOException $e) {
        error_log('Error en obtenerSaldoPesos: ' . $e->getMessage());
        return 0;
    }
}
// FIN SALDO EN PESOS

// PROMEDIO CCL
$promedio_ccl = ($contadoconliqui_compra + $contadoconliqui_venta) / 2;
if ($promedio_ccl == 0) {
    $promedio_ccl = 1; // Evitar división por cero
}
// FIN PROMEDIO CCL

// SALDO EN DÓLARES
function obtenerSaldoDolares($cliente_id, $promedio_ccl)
{
    $saldo_en_pesos = obtenerSaldoPesos($cliente_id);
    $saldo_en_dolares = $saldo_en_pesos / $promedio_ccl;
    return $saldo_en_dolares;
}
// FIN SALDO EN DÓLARES

//-- ACCIONES --//
// Renderizar Acciones
function obtenerAcciones($cliente_id)
{
    global $conexion;

    try {
        $stmt = $conexion->prepare("
            SELECT ticker, fecha, cantidad, precio 
            FROM acciones 
            WHERE cliente_id = :cliente_id
        ");
        $stmt->bindParam(':cliente_id', $cliente_id, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Error en obtenerAcciones: " . $e->getMessage());
        return [];
    }
}

function formatearFecha($fecha)
{
    try {
        $date = new DateTime($fecha);
        return $date->format('d-m-y');
    } catch (Exception $e) {
        error_log("Error en formatearFecha: " . $e->getMessage());
        return $fecha;
    }
}
// Fin Renderizar Acciones

// Precio Actual Acciones
function obtenerPrecioActualGoogleFinance($ticker)
{
    $url = "https://www.google.com/finance/quote/{$ticker}:BCBA?hl=es";

    $options = [
        "http" => [
            "header" => "User-Agent: Mozilla/5.0\r\n"
        ]
    ];
    $context = stream_context_create($options);

    $html = @file_get_contents($url, false, $context);
    if ($html === false) {
        error_log("No se pudo obtener contenido de Google Finance para el ticker: $ticker");
        return null;
    }

    libxml_use_internal_errors(true);
    $dom = new DOMDocument();
    $dom->loadHTML($html);
    libxml_clear_errors();

    $xpath = new DOMXPath($dom);
    $nodes = $xpath->query("//*[contains(@class, 'YMlKec') and contains(@class, 'fxKbKc')]");

    if ($nodes->length > 0) {
        $valor_crudo = $nodes->item(0)->nodeValue;

        // Limpieza del valor
        $valor_limpio = preg_replace('/[^\d,.-]/', '', $valor_crudo);
        $valor_limpio = str_replace('.', '', $valor_limpio);     // Eliminar separador de miles
        $valor_limpio = str_replace(',', '.', $valor_limpio);    // Convertir coma decimal a punto

        return is_numeric($valor_limpio) ? (float)$valor_limpio : null;
    } else {
        error_log("No se encontró el precio en el HTML para $ticker");
        return null;
    }
}
// Fin Precio Actual Acciones

// CCL Compra Acciones
function obtenerCCLCompra($cliente_id, $ticker)
{
    global $conexion;

    try {
        $stmt = $conexion->prepare("
            SELECT ccl_compra 
            FROM acciones 
            WHERE cliente_id = :cliente_id AND ticker = :ticker
            LIMIT 1
        ");
        $stmt->bindParam(':cliente_id', $cliente_id, PDO::PARAM_INT);
        $stmt->bindParam(':ticker', $ticker, PDO::PARAM_STR);
        $stmt->execute();

        $valor = $stmt->fetchColumn();
        return $valor !== false ? (float)$valor : null;
    } catch (PDOException $e) {
        error_log("Error en obtenerCCLCompra: " . $e->getMessage());
        return null;
    }
}
// Fin CCL Compra Acciones

// Historial Acciones
function obtenerHistorialAcciones($cliente_id)
{
    global $conexion;

    try {
        $stmt = $conexion->prepare("
            SELECT * 
            FROM acciones_historial 
            WHERE cliente_id = :cliente_id
        ");
        $stmt->bindParam(':cliente_id', $cliente_id, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Error en obtenerHistorialAcciones: " . $e->getMessage());
        return [];
    }
}

$historial_acciones = obtenerHistorialAcciones($cliente_id);
// Fin Historial Acciones

// Acciones Consolidada
function calcularValorInicialConsolidadoAccionesPesos(array $acciones): float
{
    $total = 0.0;

    foreach ($acciones as $accion) {
        $precio = isset($accion['precio']) ? (float)$accion['precio'] : 0;
        $cantidad = isset($accion['cantidad']) ? (int)$accion['cantidad'] : 0;

        $total += $precio * $cantidad;
    }

    return $total;
}
// Fin Acciones Consolidada
//-- FIN ACCIONES --//

//-- CEDEAR --//
// Renderizar Cedear
function obtenerCedear($cliente_id)
{
    global $conexion;

    try {
        $stmt = $conexion->prepare("
            SELECT ticker_cedear, fecha_cedear, cantidad_cedear, precio_cedear 
            FROM cedear 
            WHERE cliente_id = :cliente_id
        ");
        $stmt->bindParam(':cliente_id', $cliente_id, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Error en obtenerCedear: " . $e->getMessage());
        return [];
    }
}

function formatearFechaCedear($fecha)
{
    try {
        $date = new DateTime($fecha);
        return $date->format('d-m-y');
    } catch (Exception $e) {
        error_log("Error en formatearFechaCedear: " . $e->getMessage());
        return $fecha; // En caso de error, devolver la fecha original
    }
}
// Fin Renderizar Cedear

//-- Precio Actual Cedear --//
// Lista de User-Agents aleatorios
$userAgents = [
    "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36",
    "Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/119.0.0.0 Safari/537.36",
    "Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/118.0.0.0 Safari/537.36",
    "Mozilla/5.0 (iPhone; CPU iPhone OS 15_0 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/15.0 Mobile/15E148 Safari/604.1"
];

// Obtener precio con caché
function obtenerPrecioActualCedear($ticker_cedear)
{
    global $conexion, $userAgents;

    $ticker_cedear = trim($ticker_cedear);

    try {
        $sql = "SELECT precio_cedear, fecha_cedear 
                FROM cedear 
                WHERE ticker_cedear = :ticker 
                ORDER BY fecha_cedear DESC 
                LIMIT 1";
        $stmt = $conexion->prepare($sql);
        $stmt->bindParam(':ticker', $ticker_cedear);
        $stmt->execute();
        $registro = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($registro && (time() - strtotime($registro['fecha_cedear'])) < 1800) {
            return (float)$registro['precio_cedear'];
        }

        $url = "https://finance.yahoo.com/quote/{$ticker_cedear}.BA/";
        $userAgent = $userAgents[array_rand($userAgents)];

        $opts = [
            "http" => [
                "method" => "GET",
                "header" => "User-Agent: $userAgent\r\n"
            ]
        ];
        $context = stream_context_create($opts);
        $html = @file_get_contents($url, false, $context);

        if (!$html) {
            error_log("No se pudo obtener la página de Yahoo Finance para: $ticker_cedear");
            return null;
        }

        $dom = new DOMDocument();
        @$dom->loadHTML($html);
        $finder = new DomXPath($dom);
        $nodes = $finder->query("//span[@data-testid='qsp-price']");

        if ($nodes->length > 0) {
            $valor = str_replace(',', '', $nodes->item(0)->nodeValue);
            $precio = (float)$valor;

            $fecha_actual = date("Y-m-d H:i:s");
            $sqlInsert = "INSERT INTO cedear (ticker_cedear, fecha_cedear, precio_cedear)
                          VALUES (:ticker, :fecha, :precio)
                          ON DUPLICATE KEY UPDATE 
                          precio_cedear = VALUES(precio_cedear), 
                          fecha_cedear = VALUES(fecha_cedear)";
            $stmt = $conexion->prepare($sqlInsert);
            $stmt->execute([
                ':ticker' => $ticker_cedear,
                ':fecha' => $fecha_actual,
                ':precio' => $precio
            ]);

            return $precio;
        } else {
            error_log("No se encontró el nodo de precio para el ticker: $ticker_cedear");
            return null;
        }
    } catch (PDOException $e) {
        error_log("Error en obtenerPrecioActualCedear(): " . $e->getMessage());
        return null;
    }
}

// Delay inteligente
function delayAleatorio()
{
    usleep(rand(500000, 1500000)); // Entre 0.5s y 1.5s
}
//-- Fin Precio Actual Cedear --//

//-- CCL Compra Cedear --//
function obtenerCCLCompraCedear($cliente_id, $ticker_cedear)
{
    global $conexion; // Usamos PDO

    try {
        $sql = "SELECT ccl_compra_cedear 
                FROM cedear 
                WHERE cliente_id = :cliente_id AND ticker_cedear = :ticker";
        $stmt = $conexion->prepare($sql);
        $stmt->bindParam(':cliente_id', $cliente_id, PDO::PARAM_INT);
        $stmt->bindParam(':ticker', $ticker_cedear, PDO::PARAM_STR);
        $stmt->execute();

        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
        return $resultado ? $resultado['ccl_compra_cedear'] : null;
    } catch (PDOException $e) {
        error_log("Error en obtenerCCLCompraCedear(): " . $e->getMessage());
        return null;
    }
}
//-- Fin CCL Compra Cedear --//

//-- Historial Cedear --//
function obtenerHistorialCedear($cliente_id)
{
    global $conexion; // Usamos la conexión global con PDO

    try {
        $sql = "SELECT * FROM cedear_historial WHERE cliente_id = :cliente_id";
        $stmt = $conexion->prepare($sql);
        $stmt->bindParam(':cliente_id', $cliente_id, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Error en obtenerHistorialCedear(): " . $e->getMessage());
        return [];
    }
}

// Obtener el historial de cedear del cliente
$historial_cedear = obtenerHistorialCedear($cliente_id);
//-- Fin Historial Cedear --//

//-- Cedear Consolidada --//
function calcularValorInicialConsolidadoCedear($cedear)
{
    $valor_inicial_consolidado_cedear = 0.0;

    foreach ($cedear as $c) {
        // Validación de datos antes de operar
        if (isset($c['precio_cedear'], $c['cantidad_cedear'])) {
            $precio = (float) $c['precio_cedear'];
            $cantidad = (int) $c['cantidad_cedear'];
            $valor_inicial_cedear = $precio * $cantidad;
            $valor_inicial_consolidado_cedear += $valor_inicial_cedear;
        }
    }

    return $valor_inicial_consolidado_cedear;
}
//-- Fin Cedear Consolidada --//
//-- FIN CEDEAR --//

//-- BONOS --//
//-- Renderizar Bonos --//
function obtenerBonos($cliente_id)
{
    global $conexion;

    try {
        $sql = "SELECT ticker_bonos, fecha_bonos, cantidad_bonos, precio_bonos 
                FROM bonos 
                WHERE cliente_id = ?";

        $stmt = $conexion->prepare($sql);
        $stmt->execute([$cliente_id]);
        $bonos = $stmt->fetchAll();
        return $bonos;
    } catch (PDOException $e) {
        error_log("Error al obtener bonos: " . $e->getMessage());
        return [];
    }
}

function formatearFechaBonos($fecha)
{
    try {
        $date = new DateTime($fecha);
        return $date->format('d-m-y');
    } catch (Exception $e) {
        error_log("Error al formatear fecha de bonos: " . $e->getMessage());
        return $fecha; // fallback por si algo falla
    }
}
//-- Fin Renderizar Bonos --//

//-- Precio Actual Bonos --//
function obtenerValorActualRava($ticker_bonos)
{
    // Asegurarse de que el ticker no esté vacío
    if (empty($ticker_bonos)) {
        error_log("Ticker vacío al intentar obtener valor actual desde Rava.");
        return null;
    }

    $url = "https://www.rava.com/perfil/" . urlencode($ticker_bonos);

    // Obtener contenido de la página
    $html = @file_get_contents($url);

    if ($html === false) {
        error_log("No se pudo obtener contenido desde Rava para el ticker: $ticker_bonos");
        return null;
    }

    // Buscar el valor numérico después de ',&quot;ultimo&quot;:'
    $pattern = '/,&quot;ultimo&quot;:([\d.,]+)/';
    preg_match($pattern, $html, $matches);

    if (isset($matches[1])) {
        $valor = $matches[1];
        // Convertir valor con coma decimal a punto decimal
        $valor = str_replace(".", "", $valor); // Eliminar separadores de miles
        $valor = str_replace(",", ".", $valor); // Reemplazar coma decimal por punto
        $valor = (float)$valor;

        // Rava muestra el valor por 100, se ajusta dividiendo
        return $valor / 100;
    } else {
        error_log("No se pudo encontrar el valor 'ultimo' para el ticker: $ticker_bonos");
        return null;
    }
}
//-- Fin Precio Actual Bonos --//

//-- CCL Compra Bonos (usando PDO) --//
function obtenerCCLCompraBonos($cliente_id, $ticker_bonos)
{
    global $conexion;

    if (empty($ticker_bonos) || !is_numeric($cliente_id)) {
        error_log("Datos inválidos en obtenerCCLCompraBonos: cliente_id=$cliente_id, ticker_bonos=$ticker_bonos");
        return null;
    }

    try {
        $stmt = $conexion->prepare("SELECT ccl_compra FROM bonos WHERE cliente_id = :cliente_id AND ticker_bonos = :ticker");
        $stmt->execute([
            ':cliente_id' => $cliente_id,
            ':ticker' => $ticker_bonos
        ]);
        $resultado = $stmt->fetch();

        return $resultado ? $resultado['ccl_compra'] : null;
    } catch (PDOException $e) {
        error_log("Error en obtenerCCLCompraBonos: " . $e->getMessage());
        return null;
    }
}
//-- Fin CCL Compra Bonos --//

//-- Historial Bonos --//
function obtenerHistorialBonos($cliente_id)
{
    global $conexion;

    $sql = "SELECT * FROM bonos_historial WHERE cliente_id = ?";
    $stmt = $conexion->prepare($sql);

    if (!$stmt) {
        error_log("Error al preparar consulta de historial bonos (PDO).");
        return [];
    }

    $stmt->execute([$cliente_id]);
    $historial_bonos = $stmt->fetchAll();

    return $historial_bonos;
}

$historial_bonos = obtenerHistorialBonos($cliente_id);
//-- Fin Historial Bonos --//

//-- Bonos Consolidada --//
function calcularValorInicialConsolidadoBonos($bonos)
{
    $valor_inicial_consolidado_bonos = 0;

    foreach ($bonos as $bono) {
        if (isset($bono['precio_bonos'], $bono['cantidad_bonos'])) {
            $valor_inicial_bonos = $bono['precio_bonos'] * $bono['cantidad_bonos'];
            $valor_inicial_consolidado_bonos += $valor_inicial_bonos;
        }
    }

    return $valor_inicial_consolidado_bonos;
}
//-- Fin Bonos Consolidada --//
//-- FIN BONOS --//

//-- FONDOS --//
//-- Renderizar Fondos --//
function obtenerFondos($cliente_id)
{
    global $conexion;

    try {
        $sql = "SELECT ticker_fondos, fecha_fondos, cantidad_fondos, precio_fondos 
                FROM fondos 
                WHERE cliente_id = ?";

        $stmt = $conexion->prepare($sql);
        $stmt->execute([$cliente_id]);
        $fondos = $stmt->fetchAll();
        return $fondos;
    } catch (PDOException $e) {
        error_log("Error al obtener fondos: " . $e->getMessage());
        return [];
    }
}

function formatearFechaFondos($fecha)
{
    try {
        $date = new DateTime($fecha);
        return $date->format('d-m-y');
    } catch (Exception $e) {
        error_log("Error al formatear fecha de fondos: " . $e->getMessage());
        return $fecha;
    }
}
//-- Fin Renderizar Fondos --//

//-- Precio Actual Fondos --//
function obtenerValorActualRavaFondos($ticker_fondos)
{
    $url = "https://www.fondosonline.com/Information/FundData?ticker=$ticker_fondos";
    $html = @file_get_contents($url); // Silenciar errores por si la página no carga

    // Buscar el valor numérico después de '<td>Último Precio:</td>\s*<td>'
    $pattern = '/<td>Último Precio:<\/td>\s*<td>([\d.,]+)/';
    preg_match($pattern, $html, $matches);

    if (isset($matches[1])) {
        $valor = $matches[1];
        $valor = str_replace(".", "", $valor); // Eliminar separador de miles
        $valor = str_replace(",", ".", $valor); // Reemplazar coma decimal por punto
        return (float)$valor;
    } else {
        // Log opcional
        error_log("No se pudo obtener el valor actual para fondo: $ticker_fondos");
        return null;
    }
}
//-- Fin Precio Actual Fondos --//

//-- CCL Compra Fondos --//
function obtenerCCLCompraFondos($cliente_id, $ticker_fondos)
{
    global $conn;
    $sql = "SELECT ccl_compra FROM fondos WHERE cliente_id = ? AND ticker_fondos = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("is", $cliente_id, $ticker_fondos);
    $stmt->execute();
    $stmt->bind_result($valor_compra_ccl);
    $stmt->fetch();
    $stmt->close();

    return $valor_compra_ccl;
}
//-- Fin CCL Compra Fondos --//

//-- Historial Fondos --//
function obtenerHistorialFondos($cliente_id)
{
    global $conexion;

    try {
        $sql = "SELECT * FROM fondos_historial WHERE cliente_id = ?";
        $stmt = $conexion->prepare($sql);
        $stmt->execute([$cliente_id]);
        $historial_fondos = $stmt->fetchAll();
        return $historial_fondos;
    } catch (PDOException $e) {
        error_log("Error al obtener historial de fondos: " . $e->getMessage());
        return [];
    }
}

$historial_fondos = obtenerHistorialFondos($cliente_id);
//-- Fin Historial Fondos --//

//-- Fondos Consolidada --//
function calcularValorInicialConsolidadoFondos($fondos)
{
    $valor_inicial_consolidado_fondos = 0;
    foreach ($fondos as $fondo) {
        $valor_inicial_fondos = $fondo['precio_fondos'] * $fondo['cantidad_fondos'];
        $valor_inicial_consolidado_fondos += $valor_inicial_fondos;
    }
    return $valor_inicial_consolidado_fondos;
}
//-- Fin Fondos Consolidada --//
//-- FIN FONDOS --//

//-- TENENCIAS CONSOLIDADAS --//
//-- Tenencia Acciones Pesos --//
$acciones = obtenerAcciones($cliente_id);
$valor_inicial_consolidado_acciones_pesos = 0;
$valor_actual_consolidado_acciones_pesos = 0;

foreach ($acciones as $accion) {
    $precio_actual = obtenerPrecioActualGoogleFinance($accion['ticker']);

    $valor_inicial_acciones_pesos = $accion['precio'] * $accion['cantidad'];
    $valor_actual_acciones_pesos  = $precio_actual * $accion['cantidad'];

    $valor_inicial_consolidado_acciones_pesos += $valor_inicial_acciones_pesos;
    $valor_actual_consolidado_acciones_pesos  += $valor_actual_acciones_pesos;
}

$rendimiento_consolidado_acciones_pesos  = 0;
$rentabilidad_consolidado_acciones_pesos = 0;

if ($valor_inicial_consolidado_acciones_pesos != 0) {
    $rendimiento_consolidado_acciones_pesos  = $valor_actual_consolidado_acciones_pesos - $valor_inicial_consolidado_acciones_pesos;
    $rentabilidad_consolidado_acciones_pesos = ($rendimiento_consolidado_acciones_pesos / $valor_inicial_consolidado_acciones_pesos) * 100;
}
//-- Fin Tenencia Acciones Pesos --//

//-- Tenencia Acciones Dólares --//
$acciones = obtenerAcciones($cliente_id);
$valor_inicial_consolidado_acciones_dolares = 0;
$valor_actual_consolidado_acciones_dolares = 0;
$promedio_ccl = ($contadoconliqui_compra + $contadoconliqui_venta) / 2;

foreach ($acciones as $accion) {
    $valor_compra_ccl = obtenerCCLCompra($cliente_id, $accion['ticker']);
    $precio_actual = obtenerPrecioActualGoogleFinance($accion['ticker']);

    if ($accion['precio'] !== null && $accion['cantidad'] !== null && $valor_compra_ccl !== 0) {
        $valor_inicial_acciones_dolares = ($accion['precio'] * $accion['cantidad']) / $valor_compra_ccl;
        $valor_actual_acciones_dolares  = ($precio_actual * $accion['cantidad']) / $promedio_ccl;
    } else {
        $valor_inicial_acciones_dolares = 0;
        $valor_actual_acciones_dolares  = 0;
    }

    $valor_inicial_consolidado_acciones_dolares += $valor_inicial_acciones_dolares;
    $valor_actual_consolidado_acciones_dolares  += $valor_actual_acciones_dolares;
}

$rendimiento_consolidado_acciones_dolares  = 0;
$rentabilidad_consolidado_acciones_dolares = 0;

if ($valor_inicial_consolidado_acciones_dolares != 0) {
    $rendimiento_consolidado_acciones_dolares  = $valor_actual_consolidado_acciones_dolares - $valor_inicial_consolidado_acciones_dolares;
    $rentabilidad_consolidado_acciones_dolares = ($rendimiento_consolidado_acciones_dolares / $valor_inicial_consolidado_acciones_dolares) * 100;
}
//-- Fin Tenencia Acciones Dólares --//

//-- Tenencia Cedear Pesos --//
$cedear = obtenerCedear($cliente_id);
$valor_inicial_consolidado_cedear_pesos = 0;
$valor_actual_consolidado_cedear_pesos  = 0;

foreach ($cedear as $c) {
    $precio_actual = obtenerPrecioActualCedear($c['ticker_cedear']);

    $valor_inicial_cedear_pesos = $c['precio_cedear'] * $c['cantidad_cedear'];
    $valor_actual_cedear_pesos  = $precio_actual * $c['cantidad_cedear'];

    $valor_inicial_consolidado_cedear_pesos += $valor_inicial_cedear_pesos;
    $valor_actual_consolidado_cedear_pesos  += $valor_actual_cedear_pesos;
}

$rendimiento_consolidado_cedear_pesos  = 0;
$rentabilidad_consolidado_cedear_pesos = 0;

if ($valor_inicial_consolidado_cedear_pesos != 0) {
    $rendimiento_consolidado_cedear_pesos  = $valor_actual_consolidado_cedear_pesos - $valor_inicial_consolidado_cedear_pesos;
    $rentabilidad_consolidado_cedear_pesos = ($rendimiento_consolidado_cedear_pesos / $valor_inicial_consolidado_cedear_pesos) * 100;
}
//-- Fin Tenencia Cedear Pesos --//

//-- Tenencia Cedear Dólares --//
$cedear = obtenerCedear($cliente_id);
$valor_inicial_consolidado_cedear_dolares = 0;
$valor_actual_consolidado_cedear_dolares  = 0;
$promedio_ccl = ($contadoconliqui_compra + $contadoconliqui_venta) / 2;

foreach ($cedear as $c) {
    $precio_actual    = obtenerPrecioActualCedear($c['ticker_cedear']);
    $valor_compra_ccl = obtenerCCLCompraCedear($cliente_id, $c['ticker_cedear']);

    if ($valor_compra_ccl != 0) {
        $valor_inicial_cedear_dolares = ($c['precio_cedear'] * $c['cantidad_cedear']) / $valor_compra_ccl;
        $valor_actual_cedear_dolares  = ($precio_actual * $c['cantidad_cedear']) / $promedio_ccl;
    } else {
        $valor_inicial_cedear_dolares = 0;
        $valor_actual_cedear_dolares  = 0;
    }

    $valor_inicial_consolidado_cedear_dolares += $valor_inicial_cedear_dolares;
    $valor_actual_consolidado_cedear_dolares  += $valor_actual_cedear_dolares;
}

$rendimiento_consolidado_cedear_dolares  = 0;
$rentabilidad_consolidado_cedear_dolares = 0;

if ($valor_inicial_consolidado_cedear_dolares != 0) {
    $rendimiento_consolidado_cedear_dolares  = $valor_actual_consolidado_cedear_dolares - $valor_inicial_consolidado_cedear_dolares;
    $rentabilidad_consolidado_cedear_dolares = ($rendimiento_consolidado_cedear_dolares / $valor_inicial_consolidado_cedear_dolares) * 100;
}
//-- Fin Tenencia Cedear Dólares --//

//-- Tenencia Bonos Pesos --//
$bonos = obtenerBonos($cliente_id);
$valor_inicial_consolidado_bonos_pesos = 0;
$valor_actual_consolidado_bonos_pesos  = 0;

foreach ($bonos as $bono) {
    $precio_actual = obtenerValorActualRava($bono['ticker_bonos']);

    // En caso de error en la obtención del precio actual
    if ($precio_actual === null) {
        $precio_actual = 0;
    }

    $valor_inicial_bonos_pesos = $bono['precio_bonos'] * $bono['cantidad_bonos'];
    $valor_actual_bonos_pesos  = $precio_actual * $bono['cantidad_bonos'];

    $valor_inicial_consolidado_bonos_pesos += $valor_inicial_bonos_pesos;
    $valor_actual_consolidado_bonos_pesos  += $valor_actual_bonos_pesos;
}

$rendimiento_consolidado_bonos_pesos  = 0;
$rentabilidad_consolidado_bonos_pesos = 0;

if ($valor_inicial_consolidado_bonos_pesos != 0) {
    $rendimiento_consolidado_bonos_pesos  = $valor_actual_consolidado_bonos_pesos - $valor_inicial_consolidado_bonos_pesos;
    $rentabilidad_consolidado_bonos_pesos = ($rendimiento_consolidado_bonos_pesos / $valor_inicial_consolidado_bonos_pesos) * 100;
}
//-- Fin Tenencia Bonos Pesos --//

//-- Tenencia Bonos Dólares --//
$bonos = obtenerBonos($cliente_id);
$valor_inicial_consolidado_bonos_dolares = 0;
$valor_actual_consolidado_bonos_dolares  = 0;
$promedio_ccl = ($contadoconliqui_compra + $contadoconliqui_venta) / 2;

foreach ($bonos as $bono) {
    $precio_actual    = obtenerValorActualRava($bono['ticker_bonos']);
    $valor_compra_ccl = obtenerCCLCompraBonos($cliente_id, $bono['ticker_bonos']);

    // Validar valores antes de calcular
    if ($precio_actual === null || $valor_compra_ccl == 0 || $promedio_ccl == 0) {
        $precio_actual = 0;
        $valor_inicial_bonos_dolares = 0;
        $valor_actual_bonos_dolares = 0;
    } else {
        $valor_inicial_bonos_dolares = ($bono['precio_bonos'] * $bono['cantidad_bonos']) / $valor_compra_ccl;
        $valor_actual_bonos_dolares  = ($precio_actual * $bono['cantidad_bonos']) / $promedio_ccl;
    }

    $valor_inicial_consolidado_bonos_dolares += $valor_inicial_bonos_dolares;
    $valor_actual_consolidado_bonos_dolares  += $valor_actual_bonos_dolares;
}

$rendimiento_consolidado_bonos_dolares  = 0;
$rentabilidad_consolidado_bonos_dolares = 0;

if ($valor_inicial_consolidado_bonos_dolares != 0) {
    $rendimiento_consolidado_bonos_dolares  = $valor_actual_consolidado_bonos_dolares - $valor_inicial_consolidado_bonos_dolares;
    $rentabilidad_consolidado_bonos_dolares = ($rendimiento_consolidado_bonos_dolares / $valor_inicial_consolidado_bonos_dolares) * 100;
}
//-- Fin Tenencia Bonos Dólares --//

//-- Tenencia Fondos Pesos --//
$fondos = obtenerFondos($cliente_id);
$valor_inicial_consolidado_fondos_pesos = 0;
$valor_actual_consolidado_fondos_pesos = 0;

foreach ($fondos as $fondo) {
    $precio_actual = obtenerValorActualRavaFondos($fondo['ticker_fondos']);

    if ($precio_actual === null) {
        $precio_actual = 0; // O podés manejar con logs/errores visibles
    }

    $valor_inicial_fondos_pesos = $fondo['precio_fondos'] * $fondo['cantidad_fondos'];
    $valor_actual_fondos_pesos  = $precio_actual * $fondo['cantidad_fondos'];

    $valor_inicial_consolidado_fondos_pesos += $valor_inicial_fondos_pesos;
    $valor_actual_consolidado_fondos_pesos  += $valor_actual_fondos_pesos;
}

$rendimiento_consolidado_fondos_pesos  = 0;
$rentabilidad_consolidado_fondos_pesos = 0;

if ($valor_inicial_consolidado_fondos_pesos != 0) {
    $rendimiento_consolidado_fondos_pesos  = $valor_actual_consolidado_fondos_pesos - $valor_inicial_consolidado_fondos_pesos;
    $rentabilidad_consolidado_fondos_pesos = ($rendimiento_consolidado_fondos_pesos / $valor_inicial_consolidado_fondos_pesos) * 100;
}
//-- Fin Tenencia Fondos Pesos --//

//-- Tenencia Fondos Dólares --//
$fondos = obtenerFondos($cliente_id);
$valor_inicial_consolidado_fondos_dolares = 0;
$valor_actual_consolidado_fondos_dolares = 0;
$promedio_ccl = ($contadoconliqui_compra + $contadoconliqui_venta) / 2;

foreach ($fondos as $fondo) {
    $precio_actual = obtenerValorActualRavaFondos($fondo['ticker_fondos']);
    $valor_compra_ccl = obtenerCCLCompraFondos($cliente_id, $fondo['ticker_fondos']);

    if ($precio_actual === null || $valor_compra_ccl == 0 || $promedio_ccl == 0) {
        $valor_inicial_fondos_dolares = 0;
        $valor_actual_fondos_dolares = 0;
    } else {
        $valor_inicial_fondos_dolares = ($fondo['precio_fondos'] * $fondo['cantidad_fondos']) / $valor_compra_ccl;
        $valor_actual_fondos_dolares = ($precio_actual * $fondo['cantidad_fondos']) / $promedio_ccl;
    }

    $valor_inicial_consolidado_fondos_dolares += $valor_inicial_fondos_dolares;
    $valor_actual_consolidado_fondos_dolares += $valor_actual_fondos_dolares;
}

$rendimiento_consolidado_fondos_dolares = 0;
$rentabilidad_consolidado_fondos_dolares = 0;

if ($valor_inicial_consolidado_fondos_dolares != 0) {
    $rendimiento_consolidado_fondos_dolares = $valor_actual_consolidado_fondos_dolares - $valor_inicial_consolidado_fondos_dolares;
    $rentabilidad_consolidado_fondos_dolares = ($rendimiento_consolidado_fondos_dolares / $valor_inicial_consolidado_fondos_dolares) * 100;
}
//-- Fin Tenencia Fondos Dólares --//
//-- FIN TENENCIAS CONSOLIDADAS --//