<?php
require_once '../../config/config.php';

$data = json_decode(file_get_contents('php://input'), true);
$cliente_id = $data['cliente_id'];
$ticker = $data['ticker'];

// Obtener la cantidad y el precio del cedear
$sql_cedear = "SELECT cantidad_cedear, precio_cedear FROM cedear WHERE cliente_id = ? AND ticker_cedear = ?";
$stmt_cedear = $conn->prepare($sql_cedear);
$stmt_cedear->bind_param("is", $cliente_id, $ticker);
$stmt_cedear->execute();
$stmt_cedear->bind_result($cantidad, $precio);
$stmt_cedear->fetch();
$stmt_cedear->close();

if ($cantidad && $precio) {
    $valor_actualizado = $cantidad * $precio;

    // Actualizar el balance del cliente
    $sql_update_balance = "UPDATE balance SET efectivo = efectivo + ? WHERE cliente_id = ?";
    $stmt_update_balance = $conn->prepare($sql_update_balance);
    $stmt_update_balance->bind_param("di", $valor_actualizado, $cliente_id);
    $stmt_update_balance->execute();
    $stmt_update_balance->close();

    // Eliminar los cedears del cliente
    $sql_delete_cedear = "DELETE FROM cedear WHERE cliente_id = ? AND ticker_cedear = ?";
    $stmt_delete_cedear = $conn->prepare($sql_delete_cedear);
    $stmt_delete_cedear->bind_param("is", $cliente_id, $ticker);
    $stmt_delete_cedear->execute();
    $stmt_delete_cedear->close();

    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false]);
}
