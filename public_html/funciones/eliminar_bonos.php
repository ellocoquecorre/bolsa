<?php
require_once '../../config/config.php';

$data = json_decode(file_get_contents('php://input'), true);
$cliente_id = $data['cliente_id'];
$ticker = $data['ticker'];

// Obtener la cantidad y el precio del bono
$sql_bono = "SELECT cantidad_bonos, precio_bonos FROM bonos WHERE cliente_id = ? AND ticker_bonos = ?";
$stmt_bono = $conn->prepare($sql_bono);
$stmt_bono->bind_param("is", $cliente_id, $ticker);
$stmt_bono->execute();
$stmt_bono->bind_result($cantidad, $precio);
$stmt_bono->fetch();
$stmt_bono->close();

if ($cantidad && $precio) {
    $valor_actualizado = $cantidad * $precio;

    // Actualizar el balance del cliente
    $sql_update_balance = "UPDATE balance SET efectivo = efectivo + ? WHERE cliente_id = ?";
    $stmt_update_balance = $conn->prepare($sql_update_balance);
    $stmt_update_balance->bind_param("di", $valor_actualizado, $cliente_id);
    $stmt_update_balance->execute();
    $stmt_update_balance->close();

    // Eliminar los bonos del cliente
    $sql_delete_bono = "DELETE FROM bonos WHERE cliente_id = ? AND ticker_bonos = ?";
    $stmt_delete_bono = $conn->prepare($sql_delete_bono);
    $stmt_delete_bono->bind_param("is", $cliente_id, $ticker);
    $stmt_delete_bono->execute();
    $stmt_delete_bono->close();

    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false]);
}
