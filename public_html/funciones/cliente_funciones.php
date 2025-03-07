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

// RENDERIZAR ACCIONES
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
// FIN RENDERIZAR ACCIONES

// GOOGLE FINANCE
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
// FIN GOOGLE FINANCE

// CCL COMPRA
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
// FIN CCL COMPRA

// HISTORIAL ACCIONES
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
// FIN HISTORIAL DE ACCIONES

// ACCIONES CONSOLIDADA
function calcularValorInicialConsolidadoAccionesPesos($acciones)
{
    $valor_inicial_consolidado_acciones_pesos = 0;
    foreach ($acciones as $accion) {
        $valor_inicial_acciones_pesos = $accion['precio'] * $accion['cantidad'];
        $valor_inicial_consolidado_acciones_pesos += $valor_inicial_acciones_pesos;
    }
    return $valor_inicial_consolidado_acciones_pesos;
}
// FIN ACCIONES CONSOLIDADA