<?php
require_once '../../config/config.php';

$cliente_id = $_POST['cliente_id'];
$ticker = $_POST['ticker'];
$nueva_cantidad = $_POST['nueva_cantidad'];

$sql = "UPDATE acciones SET cantidad = ? WHERE cliente_id = ? AND ticker = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("iis", $nueva_cantidad, $cliente_id, $ticker);
$stmt->execute();
$stmt->close();
?>