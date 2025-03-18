<?php
// CONFIG
require_once '../../config/config.php';
// Incluir utilidades comunes
require_once 'formato_dinero.php';
include '../funciones/dolar_cronista.php';
// FIN CONFIG

// CLIENTE_ID
$cliente_id = isset($_POST['cliente_id']) ? $_POST['cliente_id'] : (isset($_GET['cliente_id']) ? $_GET['cliente_id'] : 1);
// FIN CLIENTE_ID

// DATOS DEL CLIENTE
$sql = "SELECT nombre, apellido FROM clientes WHERE cliente_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $cliente_id);
$stmt->execute();
$stmt->bind_result($nombre, $apellido);
$stmt->fetch();
$stmt->close();
// FIN DATOS DEL CLIENTE

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

$saldo_en_pesos_formateado = formatear_dinero($saldo_en_pesos);
// FIN SALDO EN PESOS

// PROMEDIO CCL
$promedio_ccl = ($contadoconliqui_compra + $contadoconliqui_venta) / 2;
// FIN PROMEDIO CCL

// SALDO EN DÓLARES
$saldo_en_dolares = $saldo_en_pesos / $promedio_ccl;
$saldo_en_dolares_formateado = formatear_dinero($saldo_en_dolares);
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

// Precio Actual Acciones
function obtenerPrecioActualGoogleFinance($ticker)
{
    $url = "https://www.google.com/finance/quote/$ticker:BCBA?hl=es";
    $html = file_get_contents($url);

    // Crear un nuevo DOMDocument
    $dom = new DOMDocument();
    @$dom->loadHTML($html);

    // Buscar el valor numérico en la etiqueta <div class="YMlKec fxKbKc">
    $finder = new DomXPath($dom);
    $classname = "YMlKec fxKbKc";
    $nodes = $finder->query("//*[contains(@class, '$classname')]");

    if ($nodes->length > 0) {
        $valor = $nodes->item(0)->nodeValue;
        // Formatear valor a número sin formato
        $valor = str_replace(",", "", $valor); // Eliminar comas de miles
        $valor = str_replace(".", "", substr($valor, 0, -3)) . "." . substr($valor, -2); // Reemplazar punto decimal y reconstruir
        $valor = (float)$valor / 100; // Corregir el formato multiplicando por 0.01
        return $valor;
    } else {
        return null;
    }
}
// Fin Precio Actual Acciones

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

// Historial Acciones
function obtenerHistorialAcciones($cliente_id)
{
    // Conexión a la base de datos
    $conn = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

    // Verificar la conexión
    if ($conn->connect_error) {
        die("Conexión fallida: " . $conn->connect_error);
    }

    // Consulta SQL
    $sql = "SELECT * FROM acciones_historial WHERE cliente_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $cliente_id);
    $stmt->execute();
    $result = $stmt->get_result();

    // Array para almacenar los resultados
    $historial_acciones = array();
    while ($row = $result->fetch_assoc()) {
        $historial_acciones[] = $row;
    }

    // Cerrar la conexión
    $stmt->close();
    $conn->close();

    return $historial_acciones;
}

// Obtener el historial de acciones del cliente
$historial_acciones = obtenerHistorialAcciones($cliente_id);
// Fin Historial Acciones

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
function obtenerPrecioActualCedear($ticker_cedear)
{
    $url = "https://www.google.com/finance/quote/$ticker_cedear:BCBA?hl=es";
    $html = file_get_contents($url);

    // Crear un nuevo DOMDocument
    $dom = new DOMDocument();
    @$dom->loadHTML($html);

    // Buscar el valor numérico en la etiqueta <div class="YMlKec fxKbKc">
    $finder = new DomXPath($dom);
    $classname = "YMlKec fxKbKc";
    $nodes = $finder->query("//*[contains(@class, '$classname')]");

    if ($nodes->length > 0) {
        $valor = $nodes->item(0)->nodeValue;
        // Formatear valor a número sin formato
        $valor = str_replace(",", "", $valor); // Eliminar comas de miles
        $valor = str_replace(".", "", substr($valor, 0, -3)) . "." . substr($valor, -2); // Reemplazar punto decimal y reconstruir
        $valor = (float)$valor / 100; // Corregir el formato multiplicando por 0.01
        return $valor;
    } else {
        return null;
    }
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
// Tenencia Acciones Pesos
$acciones = obtenerAcciones($cliente_id);
$valor_inicial_consolidado_acciones_pesos = 0;
$valor_actual_consolidado_acciones_pesos = 0;

foreach ($acciones as $accion) {
    $precio_actual = obtenerPrecioActualGoogleFinance($accion['ticker']);
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
// Fin Tenencia Acciones Pesos

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