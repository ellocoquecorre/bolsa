<?php
require_once '../../config/config.php';

header('Content-Type: application/json');

$input = json_decode(file_get_contents('php://input'), true);

if (!isset($input['cliente_id'], $input['ticker'])) {
    echo json_encode(['status' => 'error', 'message' => 'Datos incompletos']);
    exit;
}

$cliente_id = $input['cliente_id'];
$ticker = $input['ticker'];

try {
    // Obtener cantidad y precio para calcular total
    $stmt = $conexion->prepare("SELECT cantidad, precio FROM acciones WHERE cliente_id = :cliente_id AND ticker = :ticker");
    $stmt->execute([':cliente_id' => $cliente_id, ':ticker' => $ticker]);
    $acciones = $stmt->fetchAll();

    $total = 0;
    foreach ($acciones as $accion) {
        $total += $accion['cantidad'] * $accion['precio'];
    }

    // Eliminar las acciones
    $stmt = $conexion->prepare("DELETE FROM acciones WHERE cliente_id = :cliente_id AND ticker = :ticker");
    $stmt->execute([':cliente_id' => $cliente_id, ':ticker' => $ticker]);

    // Actualizar balance
    $stmt = $conexion->prepare("UPDATE balance SET efectivo = efectivo + :total WHERE cliente_id = :cliente_id");
    $stmt->execute([':total' => $total, ':cliente_id' => $cliente_id]);

    echo json_encode(['status' => 'success']);
} catch (PDOException $e) {
    error_log("Error al eliminar acción: " . $e->getMessage());
    echo json_encode(['status' => 'error', 'message' => 'Error al eliminar acción']);
}
