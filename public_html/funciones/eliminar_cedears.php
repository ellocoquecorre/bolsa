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
    $stmt = $conexion->prepare("SELECT cantidad_cedear, precio_cedear FROM cedear WHERE cliente_id = :cliente_id AND ticker_cedear = :ticker");
    $stmt->execute([':cliente_id' => $cliente_id, ':ticker' => $ticker]);
    $cedears = $stmt->fetchAll();

    $total = 0;
    foreach ($cedears as $cedear) {
        $total += $cedear['cantidad_cedear'] * $cedear['precio_cedear'];
    }

    // Eliminar los cedears
    $stmt = $conexion->prepare("DELETE FROM cedear WHERE cliente_id = :cliente_id AND ticker_cedear = :ticker");
    $stmt->execute([':cliente_id' => $cliente_id, ':ticker' => $ticker]);

    // Actualizar balance
    $stmt = $conexion->prepare("UPDATE balance SET efectivo = efectivo + :total WHERE cliente_id = :cliente_id");
    $stmt->execute([':total' => $total, ':cliente_id' => $cliente_id]);

    echo json_encode(['status' => 'success']);
} catch (PDOException $e) {
    error_log("Error al eliminar cedear: " . $e->getMessage());
    echo json_encode(['status' => 'error', 'message' => 'Error al eliminar cedear']);
}
