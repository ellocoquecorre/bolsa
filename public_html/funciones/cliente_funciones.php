<?php
// CONFIG
require_once '../../config/config.php';
// Incluir utilidades comunes
require_once 'formato_dinero.php';
include '../funciones/dolar_cronista.php';
// FIN CONFIG

// CLIENTE_ID
$cliente_id = isset($_POST['cliente_id']) ? $_POST['cliente_id'] : (isset($_GET['cliente_id']) ? $_GET['cliente_id'] : 1);

// Añadir aquí la función obtenerPromedioCCL
function obtenerPromedioCCL()
{
    global $contadoconliqui_compra, $contadoconliqui_venta;
    return ($contadoconliqui_compra + $contadoconliqui_venta) / 2;
}
// FIN CLIENTE_ID

// DATOS CLIENTE (OPTIMIZADO)
$sql_cliente = "SELECT nombre, email, telefono FROM clientes WHERE id = ?";
$stmt_cliente = $conn->prepare($sql_cliente);
$stmt_cliente->bind_param("i", $cliente_id);
$stmt_cliente->execute();
$datos_cliente = $stmt_cliente->get_result()->fetch_assoc();
$stmt_cliente->close();

$nombre = $datos_cliente['nombre'];
$email = $datos_cliente['email'];
$telefono = $datos_cliente['telefono'];
// FIN DATOS CLIENTE (OPTIMIZADO)


// CORREDORA CLIENTE
function obtenerDatosCorredora($cliente_id)
{
    global $conn;
    $sql = "SELECT url, corredora FROM clientes WHERE cliente_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $cliente_id);
    $stmt->execute();
    $stmt->bind_result($url, $corredora);
    $stmt->fetch();
    $stmt->close();
    return ['url' => $url, 'corredora' => $corredora];
}
// FIN CORREDORA CLIENTE

// SALDO EN PESOS
$sql_saldo = "SELECT efectivo FROM balance WHERE cliente_id = ?";
$stmt_saldo = $conn->prepare($sql_saldo);
$stmt_saldo->bind_param("i", $cliente_id);
$stmt_saldo->execute();
$stmt_saldo->bind_result($saldo_en_pesos);
$stmt_saldo->fetch();
$stmt_saldo->close();

$saldo_en_pesos_formateado = number_format($saldo_en_pesos, 2, '.', ',');
// FIN SALDO EN PESOS

// PROMEDIO CCL
$promedio_ccl = max(1, ($contadoconliqui_compra + $contadoconliqui_venta) / 2);
// FIN PROMEDIO CCL

// SALDO EN DÓLARES
$saldo_en_dolares = $saldo_en_pesos / $promedio_ccl;
$saldo_en_dolares_formateado = isset($mostrar_formato) && $mostrar_formato ? formatear_dinero($saldo_en_dolares) : $saldo_en_dolares_raw;
// FIN SALDO EN DÓLARES

//-- ACCIONES --//
// Renderizar Acciones
function obtenerAcciones($cliente_id)
{
    global $conn;
    $sql_acciones = "SELECT ticker, fecha, cantidad, precio FROM acciones WHERE cliente_id = ?";
    $stmt_acciones = $conn->prepare($sql_acciones);
    $stmt_acciones->bind_param("i", $cliente_id);
    $stmt_acciones->execute();
    $result = $stmt_acciones->get_result();

    $acciones = [];
    while ($fila = $result->fetch_assoc()) {
        $acciones[] = $fila;
    }

    $stmt_acciones->close();
    return $acciones;
}

function formatearFecha($fecha)
{
    $date = new DateTime($fecha);
    return $date->format('d-m-y');
}
// Fin Renderizar Acciones

// PRECIO ACTUAL ACCIONES (OPTIMIZADO)
function obtenerPrecioActualGoogleFinance($ticker)
{
    $url = "https://www.google.com/finance/quote/$ticker:BCBA?hl=es";
    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_TIMEOUT, 5); // Timeout para evitar bloqueos
    $html = curl_exec($ch);
    curl_close($ch);

    if (!$html) return null; // Manejo de errores

    $dom = new DOMDocument();
    @$dom->loadHTML($html);
    $finder = new DomXPath($dom);
    $nodes = $finder->query("//div[contains(@class, 'YMlKec fxKbKc')]");

    if ($nodes->length > 0) {
        $valor = $nodes->item(0)->nodeValue;
        $valor = str_replace(",", "", $valor);
        return (float)$valor;
    }

    return null;
}
// FIN PRECIO ACTUAL ACCIONES (OPTIMIZADO)


// CCL Compra Acciones
function obtenerCCLCompra($cliente_id, $ticker)
{
    global $conn;
    $sql = "SELECT ccl_compra FROM acciones WHERE cliente_id = ? AND ticker = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("is", $cliente_id, $ticker);
    $stmt->execute();
    $stmt->bind_result($valor_compra_ccl);
    $stmt->fetch();
    $stmt->close();
    return $valor_compra_ccl;
}
// Fin CCL Compra Acciones

// HISTORIAL ACCIONES (OPTIMIZADO)
// Se usa la conexión global en lugar de crear una nueva cada vez
function obtenerHistorialAcciones($cliente_id)
{
    global $conn;

    $sql = "SELECT * FROM acciones_historial WHERE cliente_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $cliente_id);
    $stmt->execute();
    $result = $stmt->get_result();

    // Se usa fetch_all() para obtener los resultados de manera más eficiente
    $historial_acciones = $result->fetch_all(MYSQLI_ASSOC);

    $stmt->close();
    return $historial_acciones;
}

// Obtener el historial de acciones del cliente
$historial_acciones = obtenerHistorialAcciones($cliente_id);
// FIN HISTORIAL ACCIONES (OPTIMIZADO)

// Acciones Consolidada
function calcularValorInicialConsolidadoAccionesPesos($acciones)
{
    $valor_inicial_consolidado_acciones_pesos = 0;
    foreach ($acciones as $accion) {
        $valor_inicial_acciones_pesos = $accion['precio'] * $accion['cantidad'];
        $valor_inicial_consolidado_acciones_pesos += $valor_inicial_acciones_pesos;
    }
    return $valor_inicial_consolidado_acciones_pesos;
}
// Fin Acciones Consolidada
//-- FIN ACCIONES --//

//-- CEDEAR --//
// Renderizar Cedear
function obtenerCedear($cliente_id)
{
    global $conn;
    $sql_cedear = "SELECT ticker_cedear, fecha_cedear, cantidad_cedear, precio_cedear FROM cedear WHERE cliente_id = ?";
    $stmt_cedear = $conn->prepare($sql_cedear);
    $stmt_cedear->bind_param("i", $cliente_id);
    $stmt_cedear->execute();
    $result = $stmt_cedear->get_result();

    $cedear = [];
    while ($fila = $result->fetch_assoc()) {
        $cedear[] = $fila;
    }

    $stmt_cedear->close();
    return $cedear;
}

function formatearFechaCedear($fecha)
{
    $date = new DateTime($fecha);
    return $date->format('d-m-y');
}
// Fin Renderizar Cedear

// Precio Actual Cedear
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
    global $conn, $userAgents;

    // Eliminar espacios en blanco al inicio y al final del ticker
    $ticker_cedear = trim($ticker_cedear);

    // Revisar si el precio está en la base de datos y es reciente (menos de 30 minutos)
    $sql = "SELECT precio_cedear, fecha_cedear FROM cedear WHERE ticker_cedear = ? ORDER BY fecha_cedear DESC LIMIT 1";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $ticker_cedear);
    $stmt->execute();
    $stmt->bind_result($precio_cedear, $fecha_cedear);
    $stmt->fetch();
    $stmt->close();

    if ($precio_cedear && (time() - strtotime($fecha_cedear)) < 1800) { // 1800 segundos = 30 min
        return (float)$precio_cedear;
    }

    // Si no hay precio o está desactualizado, hacer scraping
    $url = "https://finance.yahoo.com/quote/{$ticker_cedear}.BA/";
    $userAgent = $userAgents[array_rand($userAgents)]; // Elegir User-Agent aleatorio

    // Configurar opciones de stream context
    $opts = [
        "http" => [
            "method" => "GET",
            "header" => "User-Agent: $userAgent\r\n"
        ]
    ];
    $context = stream_context_create($opts);
    $html = @file_get_contents($url, false, $context);

    if (!$html) {
        // Log error
        error_log("Failed to fetch Yahoo Finance page for ticker: $ticker_cedear");
        return null;
    }

    $dom = new DOMDocument();
    @$dom->loadHTML($html);
    $finder = new DomXPath($dom);
    $nodes = $finder->query("//span[@data-testid='qsp-price']");

    if ($nodes->length > 0) {
        $valor = $nodes->item(0)->nodeValue;
        $valor = str_replace(',', '', $valor);
        $precio = (float)$valor;

        // Guardar en base de datos
        $fecha_actual = date("Y-m-d H:i:s");
        $sql = "INSERT INTO cedear (ticker_cedear, fecha_cedear, precio_cedear) VALUES (?, ?, ?) 
                ON DUPLICATE KEY UPDATE precio_cedear = VALUES(precio_cedear), fecha_cedear = VALUES(fecha_cedear)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssd", $ticker_cedear, $fecha_actual, $precio);
        $stmt->execute();
        $stmt->close();

        return $precio;
    } else {
        // Log error
        error_log("Failed to find price node for ticker: $ticker_cedear");
        return null;
    }
}

// Obtener múltiples precios en paralelo
function obtenerPreciosCedearEnParalelo($tickers)
{
    global $userAgents;

    $mh = curl_multi_init();
    $curlArray = [];
    $responses = [];

    foreach ($tickers as $ticker) {
        // Eliminar espacios en blanco al inicio y al final del ticker
        $ticker = trim($ticker);

        $url = "https://finance.yahoo.com/quote/{$ticker}.BA/";
        $userAgent = $userAgents[array_rand($userAgents)];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_USERAGENT, $userAgent);

        $curlArray[$ticker] = $ch;
        curl_multi_add_handle($mh, $ch);
    }

    // Ejecutar todas las peticiones en paralelo
    $running = null;
    do {
        curl_multi_exec($mh, $running);
        curl_multi_select($mh);
    } while ($running > 0);

    // Obtener resultados
    foreach ($curlArray as $ticker => $ch) {
        $html = curl_multi_getcontent($ch);
        curl_multi_remove_handle($mh, $ch);
        curl_close($ch);

        if ($html) {
            $dom = new DOMDocument();
            @$dom->loadHTML($html);
            $finder = new DomXPath($dom);
            $nodes = $finder->query("//span[@data-testid='qsp-price']");

            if ($nodes->length > 0) {
                $valor = $nodes->item(0)->nodeValue;
                $valor = str_replace(',', '', $valor);
                $precio = (float)$valor;

                // Guardar en base de datos
                global $conn;
                $fecha_actual = date("Y-m-d H:i:s");
                $sql = "INSERT INTO cedear (ticker_cedear, fecha_cedear, precio_cedear) VALUES (?, ?, ?) 
                        ON DUPLICATE KEY UPDATE precio_cedear = VALUES(precio_cedear), fecha_cedear = VALUES(fecha_cedear)";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("ssd", $ticker, $fecha_actual, $precio);
                $stmt->execute();
                $stmt->close();

                $responses[$ticker] = $precio;
            } else {
                $responses[$ticker] = null;
            }
        }
    }

    curl_multi_close($mh);
    return $responses;
}

// Delay inteligente
function delayAleatorio()
{
    usleep(rand(500000, 1500000)); // Entre 0.5s y 1.5s
}
// Fin Precio Actual Cedear

// CCL Compra Cedear
function obtenerCCLCompraCedear($cliente_id, $ticker_cedear)
{
    global $conn;
    $sql = "SELECT ccl_compra_cedear FROM cedear WHERE cliente_id = ? AND ticker_cedear = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("is", $cliente_id, $ticker_cedear);
    $stmt->execute();
    $stmt->bind_result($valor_compra_ccl);
    $stmt->fetch();
    $stmt->close();
    return $valor_compra_ccl;
}
// Fin CCL Compra Cedear

// Historial Cedear
function obtenerHistorialCedear($cliente_id)
{
    // Conexión a la base de datos
    $conn = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

    // Verificar la conexión
    if ($conn->connect_error) {
        die("Conexión fallida: " . $conn->connect_error);
    }

    // Consulta SQL
    $sql = "SELECT * FROM cedear_historial WHERE cliente_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $cliente_id);
    $stmt->execute();
    $result = $stmt->get_result();

    // Array para almacenar los resultados
    $historial_cedear = array();
    while ($row = $result->fetch_assoc()) {
        $historial_cedear[] = $row;
    }

    // Cerrar la conexión
    $stmt->close();
    $conn->close();

    return $historial_cedear;
}

// Obtener el historial de cedear del cliente
$historial_cedear = obtenerHistorialCedear($cliente_id);
// Fin Historial Cedear

// Cedear Consolidada
function calcularValorInicialConsolidadoCedear($cedear)
{
    $valor_inicial_consolidado_cedear = 0;
    foreach ($cedear as $c) {
        $valor_inicial_cedear = $c['precio_cedear'] * $c['cantidad_cedear'];
        $valor_inicial_consolidado_cedear += $valor_inicial_cedear;
    }
    return $valor_inicial_consolidado_cedear;
}
// Fin Cedear Consolidada
//-- FIN CEDEAR --//

//-- BONOS --//
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

function formatearFechaBonos($fecha)
{
    $date = new DateTime($fecha);
    return $date->format('d-m-y');
}
// Fin Renderizar Bonos

// Precio Actual Bonos
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
        // Dividir el valor final por 100
        $valor = $valor / 100;
        return $valor;
    } else {
        return null;
    }
}
// Fin Precio Actual Bonos

// CCL Compra Bonos
function obtenerCCLCompraBonos($cliente_id, $ticker_bonos)
{
    global $conn;
    $sql = "SELECT ccl_compra FROM bonos WHERE cliente_id = ? AND ticker_bonos = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("is", $cliente_id, $ticker_bonos);
    $stmt->execute();
    $stmt->bind_result($valor_compra_ccl);
    $stmt->fetch();
    $stmt->close();
    return $valor_compra_ccl;
}
// Fin CCL Compra Bonos

// Historial Bonos
function obtenerHistorialBonos($cliente_id)
{
    // Conexión a la base de datos
    $conn = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

    // Verificar la conexión
    if ($conn->connect_error) {
        die("Conexión fallida: " . $conn->connect_error);
    }

    // Consulta SQL
    $sql = "SELECT * FROM bonos_historial WHERE cliente_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $cliente_id);
    $stmt->execute();
    $result = $stmt->get_result();

    // Array para almacenar los resultados
    $historial_bonos = array();
    while ($row = $result->fetch_assoc()) {
        $historial_bonos[] = $row;
    }

    // Cerrar la conexión
    $stmt->close();
    $conn->close();

    return $historial_bonos;
}

// Obtener el historial de bonos del cliente
$historial_bonos = obtenerHistorialBonos($cliente_id);
// Fin Historial Bonos

// Bonos Consolidada
function calcularValorInicialConsolidadoBonos($bonos)
{
    $valor_inicial_consolidado_bonos = 0;
    foreach ($bonos as $bono) {
        $valor_inicial_bonos = $bono['precio_bonos'] * $bono['cantidad_bonos'];
        $valor_inicial_consolidado_bonos += $valor_inicial_bonos;
    }
    return $valor_inicial_consolidado_bonos;
}
// Fin Bonos Consolidada
//-- FIN BONOS --//

//-- FONDOS --//
// Renderizar Fondos
function obtenerFondos($cliente_id)
{
    global $conn;
    $sql_fondos = "SELECT ticker_fondos, fecha_fondos, cantidad_fondos, precio_fondos FROM fondos WHERE cliente_id = ?";
    $stmt_fondos = $conn->prepare($sql_fondos);
    $stmt_fondos->bind_param("i", $cliente_id);
    $stmt_fondos->execute();
    $result = $stmt_fondos->get_result();

    $fondos = [];
    while ($fila = $result->fetch_assoc()) {
        $fondos[] = $fila;
    }

    $stmt_fondos->close();
    return $fondos;
}

function formatearFechaFondos($fecha)
{
    $date = new DateTime($fecha);
    return $date->format('d-m-y');
}
// Fin Renderizar Fondos

// Precio Actual Fondos
function obtenerValorActualRavaFondos($ticker_fondos)
{
    $url = "https://www.fondosonline.com/Information/FundData?ticker=$ticker_fondos";
    $html = file_get_contents($url);

    // Buscar el valor numérico después de '<td>Último Precio:</td>\s*<td>'
    $pattern = '/<td>Último Precio:<\/td>\s*<td>([\d.,]+)/';
    preg_match($pattern, $html, $matches);

    if (isset($matches[1])) {
        $valor = $matches[1];
        // Eliminar separador de miles
        $valor = str_replace(".", "", $valor);
        // Reemplazar coma decimal por punto
        $valor = str_replace(",", ".", $valor);
        $valor = (float)$valor;
        return $valor;
    } else {
        return null;
    }
}
// Fin Precio Actual Fondos

// CCL Compra Fondos
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
// Fin CCL Compra Fondos

// Historial Fondos
function obtenerHistorialFondos($cliente_id)
{
    // Conexión a la base de datos
    $conn = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

    // Verificar la conexión
    if ($conn->connect_error) {
        die("Conexión fallida: " . $conn->connect_error);
    }

    // Consulta SQL
    $sql = "SELECT * FROM fondos_historial WHERE cliente_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $cliente_id);
    $stmt->execute();
    $result = $stmt->get_result();

    // Array para almacenar los resultados
    $historial_fondos = array();
    while ($row = $result->fetch_assoc()) {
        $historial_fondos[] = $row;
    }

    // Cerrar la conexión
    $stmt->close();
    $conn->close();

    return $historial_fondos;
}

// Obtener el historial de fondos del cliente
$historial_fondos = obtenerHistorialFondos($cliente_id);
// Fin Historial Fondos

// Fondos Consolidada
function calcularValorInicialConsolidadoFondos($fondos)
{
    $valor_inicial_consolidado_fondos = 0;
    foreach ($fondos as $fondo) {
        $valor_inicial_fondos = $fondo['precio_fondos'] * $fondo['cantidad_fondos'];
        $valor_inicial_consolidado_fondos += $valor_inicial_fondos;
    }
    return $valor_inicial_consolidado_fondos;
}
// Fin Fondos Consolidada
//-- FIN FONDOS --//

//-- TENENCIAS CONSOLIDADAS --//
// TENENCIA ACCIONES PESOS
$acciones = obtenerAcciones($cliente_id);
$valor_inicial_consolidado_acciones_pesos = 0;
$valor_actual_consolidado_acciones_pesos = 0;

// Array para almacenar precios actuales y evitar múltiples llamadas externas
$precios_actuales = [];

foreach ($acciones as $accion) {
    // Consultar precio solo si no lo tenemos en caché
    if (!isset($precios_actuales[$accion['ticker']])) {
        $precios_actuales[$accion['ticker']] = obtenerPrecioActualGoogleFinance($accion['ticker']);
    }
    $precio_actual = $precios_actuales[$accion['ticker']];

    $valor_inicial_acciones_pesos = $accion['precio'] * $accion['cantidad'];
    $valor_inicial_consolidado_acciones_pesos += $valor_inicial_acciones_pesos;

    $valor_actual_acciones_pesos = $precio_actual * $accion['cantidad'];
    $valor_actual_consolidado_acciones_pesos += $valor_actual_acciones_pesos;
}

$rendimiento_consolidado_acciones_pesos = 0;
$rentabilidad_consolidado_acciones_pesos = 0;

if ($valor_inicial_consolidado_acciones_pesos != 0) {
    $rendimiento_consolidado_acciones_pesos = $valor_actual_consolidado_acciones_pesos - $valor_inicial_consolidado_acciones_pesos;
    $rentabilidad_consolidado_acciones_pesos = (($valor_actual_consolidado_acciones_pesos - $valor_inicial_consolidado_acciones_pesos) / $valor_inicial_consolidado_acciones_pesos) * 100;
}
// FIN TENENCIA ACCIONES PESOS


// Tenencia Acciones Dolares
$acciones = obtenerAcciones($cliente_id);
$valor_inicial_consolidado_acciones_dolares = 0;
$valor_actual_consolidado_acciones_dolares = 0;
$promedio_ccl = ($contadoconliqui_compra + $contadoconliqui_venta) / 2;

foreach ($acciones as $accion) {
    $valor_compra_ccl = obtenerCCLCompra($cliente_id, $accion['ticker']);
    $precio_actual = obtenerPrecioActualGoogleFinance($accion['ticker']);

    if ($accion['precio'] !== null && $accion['cantidad'] !== null && $valor_compra_ccl !== 0) {
        $valor_inicial_acciones_dolares = ($accion['precio'] * $accion['cantidad']) / $valor_compra_ccl;
        $valor_actual_acciones_dolares = ($precio_actual * $accion['cantidad']) / $promedio_ccl;
    } else {
        $valor_inicial_acciones_dolares = 0; // O maneja el caso según sea necesario
        $valor_actual_acciones_dolares = 0; // O maneja el caso según sea necesario
    }

    $valor_inicial_consolidado_acciones_dolares += $valor_inicial_acciones_dolares;
    $valor_actual_consolidado_acciones_dolares += $valor_actual_acciones_dolares;
}

$rendimiento_consolidado_acciones_dolares = 0;
$rentabilidad_consolidado_acciones_dolares = 0;

if ($valor_inicial_consolidado_acciones_dolares != 0) {
    $rendimiento_consolidado_acciones_dolares = $valor_actual_consolidado_acciones_dolares - $valor_inicial_consolidado_acciones_dolares;
    $rentabilidad_consolidado_acciones_dolares = (($valor_actual_consolidado_acciones_dolares - $valor_inicial_consolidado_acciones_dolares) / $valor_inicial_consolidado_acciones_dolares) * 100;
}
// Fin Tenencia Acciones Dolares

// Tenencia Cedear Pesos
$cedear = obtenerCedear($cliente_id);
$valor_inicial_consolidado_cedear_pesos = 0;
$valor_actual_consolidado_cedear_pesos = 0;

foreach ($cedear as $c) {
    $precio_actual = obtenerPrecioActualCedear($c['ticker_cedear']);
    $valor_inicial_cedear_pesos = $c['precio_cedear'] * $c['cantidad_cedear'];
    $valor_inicial_consolidado_cedear_pesos += $valor_inicial_cedear_pesos;
    $valor_actual_cedear_pesos = $precio_actual * $c['cantidad_cedear'];
    $valor_actual_consolidado_cedear_pesos += $valor_actual_cedear_pesos;
}

$rendimiento_consolidado_cedear_pesos = 0;
$rentabilidad_consolidado_cedear_pesos = 0;

if ($valor_inicial_consolidado_cedear_pesos != 0) {
    $rendimiento_consolidado_cedear_pesos = $valor_actual_consolidado_cedear_pesos - $valor_inicial_consolidado_cedear_pesos;
    $rentabilidad_consolidado_cedear_pesos = (($valor_actual_consolidado_cedear_pesos - $valor_inicial_consolidado_cedear_pesos) / $valor_inicial_consolidado_cedear_pesos) * 100;
}
// Fin Tenencia Cedear Pesos

// Tenencia Cedear Dolares
$cedear = obtenerCedear($cliente_id);
$valor_inicial_consolidado_cedear_dolares = 0;
$valor_actual_consolidado_cedear_dolares = 0;
$promedio_ccl = ($contadoconliqui_compra + $contadoconliqui_venta) / 2;

foreach ($cedear as $c) {
    $precio_actual = obtenerPrecioActualCedear($c['ticker_cedear']);
    $valor_compra_ccl = obtenerCCLCompraCedear($cliente_id, $c['ticker_cedear']);
    $valor_inicial_cedear_dolares = ($c['precio_cedear'] * $c['cantidad_cedear']) / $valor_compra_ccl;
    $valor_inicial_consolidado_cedear_dolares += $valor_inicial_cedear_dolares;
    $valor_actual_cedear_dolares = ($precio_actual * $c['cantidad_cedear']) / $promedio_ccl;
    $valor_actual_consolidado_cedear_dolares += $valor_actual_cedear_dolares;
}

$rendimiento_consolidado_cedear_dolares = 0;
$rentabilidad_consolidado_cedear_dolares = 0;

if ($valor_inicial_consolidado_cedear_dolares != 0) {
    $rendimiento_consolidado_cedear_dolares = $valor_actual_consolidado_cedear_dolares - $valor_inicial_consolidado_cedear_dolares;
    $rentabilidad_consolidado_cedear_dolares = (($valor_actual_consolidado_cedear_dolares - $valor_inicial_consolidado_cedear_dolares) / $valor_inicial_consolidado_cedear_dolares) * 100;
}
// Fin Tenencia Cedear Dolares

// Tenencia Bonos Pesos
$bonos = obtenerBonos($cliente_id);
$valor_inicial_consolidado_bonos_pesos = 0;
$valor_actual_consolidado_bonos_pesos = 0;

foreach ($bonos as $bono) {
    $precio_actual = obtenerValorActualRava($bono['ticker_bonos']);
    $valor_inicial_bonos_pesos = $bono['precio_bonos'] * $bono['cantidad_bonos'];
    $valor_inicial_consolidado_bonos_pesos += $valor_inicial_bonos_pesos;
    $valor_actual_bonos_pesos = $precio_actual * $bono['cantidad_bonos'];
    $valor_actual_consolidado_bonos_pesos += $valor_actual_bonos_pesos;
}

$rendimiento_consolidado_bonos_pesos = 0;
$rentabilidad_consolidado_bonos_pesos = 0;

if ($valor_inicial_consolidado_bonos_pesos != 0) {
    $rendimiento_consolidado_bonos_pesos = $valor_actual_consolidado_bonos_pesos - $valor_inicial_consolidado_bonos_pesos;
    $rentabilidad_consolidado_bonos_pesos = (($valor_actual_consolidado_bonos_pesos - $valor_inicial_consolidado_bonos_pesos) / $valor_inicial_consolidado_bonos_pesos) * 100;
}
// Fin Tenencia Bonos Pesos

// Tenencia Bonos Dolares
$bonos = obtenerBonos($cliente_id);
$valor_inicial_consolidado_bonos_dolares = 0;
$valor_actual_consolidado_bonos_dolares = 0;
$promedio_ccl = ($contadoconliqui_compra + $contadoconliqui_venta) / 2;

foreach ($bonos as $bono) {
    $precio_actual = obtenerValorActualRava($bono['ticker_bonos']);
    $valor_compra_ccl = obtenerCCLCompraBonos($cliente_id, $bono['ticker_bonos']);
    $valor_inicial_bonos_dolares = ($bono['precio_bonos'] * $bono['cantidad_bonos']) / $valor_compra_ccl;
    $valor_inicial_consolidado_bonos_dolares += $valor_inicial_bonos_dolares;
    $valor_actual_bonos_dolares = ($precio_actual * $bono['cantidad_bonos']) / $promedio_ccl;
    $valor_actual_consolidado_bonos_dolares += $valor_actual_bonos_dolares;
}

$rendimiento_consolidado_bonos_dolares = 0;
$rentabilidad_consolidado_bonos_dolares = 0;

if ($valor_inicial_consolidado_bonos_dolares != 0) {
    $rendimiento_consolidado_bonos_dolares = $valor_actual_consolidado_bonos_dolares - $valor_inicial_consolidado_bonos_dolares;
    $rentabilidad_consolidado_bonos_dolares = (($valor_actual_consolidado_bonos_dolares - $valor_inicial_consolidado_bonos_dolares) / $valor_inicial_consolidado_bonos_dolares) * 100;
}
// Fin Tenencia Bonos Dolares

// Tenencia Fondos Pesos
$fondos = obtenerFondos($cliente_id);
$valor_inicial_consolidado_fondos_pesos = 0;
$valor_actual_consolidado_fondos_pesos = 0;

foreach ($fondos as $fondo) {
    $precio_actual = obtenerValorActualRavaFondos($fondo['ticker_fondos']);
    $valor_inicial_fondos_pesos = $fondo['precio_fondos'] * $fondo['cantidad_fondos'];
    $valor_inicial_consolidado_fondos_pesos += $valor_inicial_fondos_pesos;
    $valor_actual_fondos_pesos = $precio_actual * $fondo['cantidad_fondos'];
    $valor_actual_consolidado_fondos_pesos += $valor_actual_fondos_pesos;
}

$rendimiento_consolidado_fondos_pesos = 0;
$rentabilidad_consolidado_fondos_pesos = 0;

if ($valor_inicial_consolidado_fondos_pesos != 0) {
    $rendimiento_consolidado_fondos_pesos = $valor_actual_consolidado_fondos_pesos - $valor_inicial_consolidado_fondos_pesos;
    $rentabilidad_consolidado_fondos_pesos = (($valor_actual_consolidado_fondos_pesos - $valor_inicial_consolidado_fondos_pesos) / $valor_inicial_consolidado_fondos_pesos) * 100;
}
// Fin Tenencia Fondos Pesos

// Tenencia Fondos Dolares
$fondos = obtenerFondos($cliente_id);
$valor_inicial_consolidado_fondos_dolares = 0;
$valor_actual_consolidado_fondos_dolares = 0;
$promedio_ccl = ($contadoconliqui_compra + $contadoconliqui_venta) / 2;

foreach ($fondos as $fondo) {
    $precio_actual = obtenerValorActualRavaFondos($fondo['ticker_fondos']);
    $valor_compra_ccl = obtenerCCLCompraFondos($cliente_id, $fondo['ticker_fondos']);
    $valor_inicial_fondos_dolares = ($fondo['precio_fondos'] * $fondo['cantidad_fondos']) / $valor_compra_ccl;
    $valor_inicial_consolidado_fondos_dolares += $valor_inicial_fondos_dolares;
    $valor_actual_fondos_dolares = ($precio_actual * $fondo['cantidad_fondos']) / $promedio_ccl;
    $valor_actual_consolidado_fondos_dolares += $valor_actual_fondos_dolares;
}

$rendimiento_consolidado_fondos_dolares = 0;
$rentabilidad_consolidado_fondos_dolares = 0;

if ($valor_inicial_consolidado_fondos_dolares != 0) {
    $rendimiento_consolidado_fondos_dolares = $valor_actual_consolidado_fondos_dolares - $valor_inicial_consolidado_fondos_dolares;
    $rentabilidad_consolidado_fondos_dolares = (($valor_actual_consolidado_fondos_dolares - $valor_inicial_consolidado_fondos_dolares) / $valor_inicial_consolidado_fondos_dolares) * 100;
}
// Fin Tenencia Fondos Dolares
//-- FIN TENENCIAS CONSOLIDADAS --//