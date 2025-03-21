<?php
require_once '../../config/config.php';

header('Content-Type: application/json');

$input = file_get_contents('php://input');
$data = json_decode($input, true);

if (isset($data['cliente_id'])) {
    $cliente_id = intval($data['cliente_id']);

    $tables = [
        'acciones',
        'acciones_historial',
        'balance',
        'bonos',
        'bonos_historial',
        'cedear',
        'cedear_historial',
        'clientes',
        'fondos',
        'fondos_historial'
    ];

    $conn->begin_transaction();

    try {
        foreach ($tables as $table) {
            $stmt = $conn->prepare("DELETE FROM $table WHERE cliente_id = ?");
            $stmt->bind_param("i", $cliente_id);
            $stmt->execute();
            $stmt->close();
        }

        $conn->commit();
        echo json_encode(['status' => 'success']);
    } catch (Exception $e) {
        $conn->rollback();
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    }

    $conn->close();
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request']);
}
