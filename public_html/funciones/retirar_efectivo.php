<?php
require_once '../../config/config.php';
require_once 'formato_dinero.php';

// Verificar que se recibió el cliente_id y el monto
if (!isset($_POST['cliente_id']) || !isset($_POST['monto'])) {
    echo json_encode(["error" => "Faltan datos"]);
    exit;
}

$cliente_id = (int)$_POST['cliente_id'];
$monto = (float)str_replace(',', '.', $_POST['monto']); // Convertir coma a punto decimal

// Obtener saldo actual
$sql = "SELECT efectivo FROM balance WHERE cliente_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $cliente_id);
$stmt->execute();
$stmt->bind_result($saldo_actual);
$stmt->fetch();
$stmt->close();

// Verificar si el monto a retirar es mayor que el saldo actual
if ($monto > $saldo_actual) {
    echo json_encode(["error" => "Saldo insuficiente"]);
    exit;
}

// Calcular nuevo saldo
$nuevo_saldo = $saldo_actual - $monto;

// Actualizar saldo en la base de datos
$sql_update = "UPDATE balance SET efectivo = ? WHERE cliente_id = ?";
$stmt_update = $conn->prepare($sql_update);
$stmt_update->bind_param("di", $nuevo_saldo, $cliente_id);
$stmt_update->execute();
$stmt_update->close();

// Calcular saldo en dólares
include 'dolar_cronista.php';
$promedio_ccl = ($contadoconliqui_compra + $contadoconliqui_venta) / 2;
$saldo_en_dolares = $nuevo_saldo / $promedio_ccl;

// Devolver los nuevos saldos formateados
echo json_encode([
    "saldo_pesos" => formatear_dinero($nuevo_saldo),
    "saldo_dolares" => formatear_dinero($saldo_en_dolares)
]);
