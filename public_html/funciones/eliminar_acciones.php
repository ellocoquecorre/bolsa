<?php
require_once '../../config/config.php';

$cliente_id = $_POST['cliente_id'];
$ticker = $_POST['ticker'];

// Obtener los valores de cantidad y precio antes de eliminar
$sql = "SELECT cantidad, precio FROM acciones WHERE cliente_id = ? AND ticker = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('is', $cliente_id, $ticker);
$stmt->execute();
$result = $stmt->get_result();

$total = 0;

while ($row = $result->fetch_assoc()) {
    $cantidad = $row['cantidad'];
    $precio = $row['precio'];
    $total += $cantidad * $precio;
}

$stmt->close();

// Eliminar las acciones del cliente
$sql = "DELETE FROM acciones WHERE cliente_id = ? AND ticker = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('is', $cliente_id, $ticker);
$stmt->execute();
$stmt->close();

// Actualizar el balance del cliente
$sql = "UPDATE balance SET efectivo = efectivo + ? WHERE cliente_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('di', $total, $cliente_id);
$stmt->execute();
$stmt->close();

$response = ['status' => 'success'];
echo json_encode($response);
