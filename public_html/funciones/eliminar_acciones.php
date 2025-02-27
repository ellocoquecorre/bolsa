<?php
require_once '../../config/config.php';

$data = json_decode(file_get_contents('php://input'), true);
$cliente_id = $data['cliente_id'];
$ticker = $data['ticker'];

// Obtener la cantidad y el precio de la acción
$sql_accion = "SELECT cantidad, precio FROM acciones WHERE cliente_id = ? AND ticker = ?";
$stmt_accion = $conn->prepare($sql_accion);
$stmt_accion->bind_param("is", $cliente_id, $ticker);
$stmt_accion->execute();
$stmt_accion->bind_result($cantidad, $precio);
$stmt_accion->fetch();
$stmt_accion->close();

if ($cantidad && $precio) {
    $valor_actualizado = $cantidad * $precio;

    // Actualizar el balance del cliente
    $sql_update_balance = "UPDATE balance SET efectivo = efectivo + ? WHERE cliente_id = ?";
    $stmt_update_balance = $conn->prepare($sql_update_balance);
    $stmt_update_balance->bind_param("di", $valor_actualizado, $cliente_id);
    $stmt_update_balance->execute();
    $stmt_update_balance->close();

    // Eliminar las acciones del cliente
    $sql_delete_accion = "DELETE FROM acciones WHERE cliente_id = ? AND ticker = ?";
    $stmt_delete_accion = $conn->prepare($sql_delete_accion);
    $stmt_delete_accion->bind_param("is", $cliente_id, $ticker);
    $stmt_delete_accion->execute();
    $stmt_delete_accion->close();

    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false]);
}
