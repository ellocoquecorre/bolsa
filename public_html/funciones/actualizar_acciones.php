<?php
require_once '../../config/config.php';

$cliente_id = $_POST['cliente_id'];
$ticker = $_POST['ticker'];
$cantidad = $_POST['cantidad'];
$fecha_compra = date('Y-m-d', strtotime($_POST['fecha_compra']));
$precio_compra = $_POST['precio_compra'];
$ccl_compra = $_POST['ccl_compra'];
$fecha_venta = date('Y-m-d', strtotime($_POST['fecha_venta']));
$precio_venta = $_POST['precio_venta'];
$ccl_venta = $_POST['ccl_venta'];

$sql = "INSERT INTO acciones_historial (cliente_id, ticker, cantidad, fecha_compra, precio_compra, ccl_compra, fecha_venta, precio_venta, ccl_venta)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("isissssss", $cliente_id, $ticker, $cantidad, $fecha_compra, $precio_compra, $ccl_compra, $fecha_venta, $precio_venta, $ccl_venta);
$stmt->execute();
$stmt->close();
?>